<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BikerApplicationRequest;
use App\Http\Requests\Api\PictureRequest;
use App\Jobs\OrderJob;
use App\Jobs\SendSingleNotificationJob;
use App\Jobs\SMSJob;
use App\Models\BikerRequest;
use App\Models\Order;
use Storage;

class BikerController extends Controller
{
    // get the latest verification of the auth user
    public function latestVerification() {
        $bikerVerification = BikerRequest::where('user_id', auth('api')->user()->id)->orderBy('created_at', 'DESC')->firstOrFail();
        return response()->json(['data' => $bikerVerification], 200);
    }

    // post the verification
    public function postVerification(BikerApplicationRequest $request) {
        activity()->disableLogging();

        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
        $bikerVerification = BikerRequest::create(array_merge($request->getData(),
            [
            'status' => 'Pending',
            'user_id' => auth('api')->user()->id,
            'credential_name' => $result->getPublicId(), 'credential_file_path' => $result->getPath()
            ]
        ));

        return response()->json(['data' => $bikerVerification], 201);
    }

    // get auth delivery analytics
    public function getAuthAnalytics() {
        // get total earnings count
        $completedOrder = Order::where('delivered_by', auth('api')->user()->id)
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'Received')
            ->where('delivery_payment_status', 'Received')->get();

        $totalEarnings = $completedOrder->sum('delivery_fee');
        $totalCompletedOrder = $completedOrder->count();

        $totalUnprocessedDelivery = Order::where('delivered_by', auth('api')->user()->id)
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'Received')
            ->where('delivery_payment_status', '!=', 'Received')->count();

        $totalPendingDelivery = Order::where('delivered_by', auth('api')->user()->id)
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', '!=','Received')
            ->count();

        $totalReturnableItem = Order::where('delivered_by', auth('api')->user()->id)
            ->where('pick_up_type', 'Delivery')
            ->where('order_status', 'DNR')
            ->where('is_returned', 'No')
            ->count();



        return response()->json([
            'totalEarnings' => $totalEarnings,
            'totalCompletedOrder' => $totalCompletedOrder,
            'totalUnprocessedDelivery' => $totalUnprocessedDelivery,
            'totalPendingDelivery' => $totalPendingDelivery,
            'totalReturnableItem' => $totalReturnableItem,
        ], 200);

    }

    // get Auth delivery transaction
    public function getAuthTransaction() {
        $orders = Order::where('delivered_by', auth('api')->user()->id)
        ->where('pick_up_type', 'Delivery')
        ->orderBy('pickup_date', 'ASC')
        ->get();
        return response()->json(['data' => $orders], 200);
    }

    // get the list of approved delivery order request without any booked biker
    public function getListOrders() {
        $orders = Order::where('application_status', 'Approved')
            ->where('pick_up_type', 'Delivery')
            ->where('delivered_by', null)
            ->orderBy('pickup_date', 'ASC')
            ->get();
        return response()->json(['data' => $orders], 200);
    }

    // get Order Details
    public function getOrderDetails(Order $order) {
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }
        return response()->json(['data' => $order->load('certificateForms', 'orderReports')], 200);
    }

    // put booked the order
    public function bookedOrder(Order $order) {
        activity()->disableLogging();
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != null) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        if ($order->ordered_by == auth('api')->user()->id) {
            return response()->json(['message' => 'You can not deliver your own requested certificate'], 403);
        }

        if (Order::where('delivered_by', auth('api')->user()->id)->where('pick_up_type', 'Delivery')->where('order_status', '!=','Received')->count() > 2) {
            return response()->json(['message' => 'You have to much pending delivery, please complete your other deliveries'], 403);
        }

        $order->fill([
            'delivered_by' => auth('api')->user()->id,
            'order_status' => 'Accepted'
        ])->save();

        $subject = 'Certificate Order Notification';
        $emailMessage = 'Your order #'.$order->id. ' has been booked by our biker delivery Please prepare the exact payment';
        $message = 'Your order #'.$order->id. ' has been booked by our biker delivery Please prepare the exact payment.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
        // send sms and email notification to the person who orders it

        dispatch(
            new SendSingleNotificationJob(
                $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                $emailMessage, $order->id,  "App\Models\Order"
        ));

        dispatch(new OrderJob($order, $subject, $emailMessage));
        dispatch(new SMSJob($order->phone_no, $message));

        return response()->json(['message' => 'Order has been selected successfully'], 200);
    }

    // start riding biker
    public function startRiding(Order $order) {

        if ($order->order_status != 'On-Going') {
            return response()->json(['message' => 'You cannot start riding since you did not pickup the order yet. Go to the barangay to pickup the order'], 403);
        }

        activity()->disableLogging();
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        $subject = 'Certificate Order Notification';
        $emailMessage = 'Your order #'.$order->id. ' has been start delivering your requested order by the biker. Please prepare the exact payment.';
        $smsMessage = 'Your order #'.$order->id. ' has been start delivering your requested order by the biker. Please prepare the exact payment.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
        // send sms and email notification to the person who orders it
        dispatch(
            new SendSingleNotificationJob(
                $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                $emailMessage, $order->id,  "App\Models\Order"
        ));

        dispatch(new OrderJob($order, $subject, $emailMessage));
        dispatch(new SMSJob($order->phone_no, $smsMessage));

        return response()->json(['message' => 'Notification has been set to the user.'], 200);
    }

    // put confirm receive order
    public function confirmReceiveOrder(PictureRequest $request, Order $order) {
        activity()->disableLogging();

        if ($order->order_status != 'On-Going') {
            return response()->json(['message' => 'You cannot start riding since you did not pickup the order yet. Go to the barangay to pickup the order'], 403);
        }

        if ($order->application_status != 'Approved' && $order->pick_up_type != 'Delivery' && $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        if ($order->order_status == 'Received') {
            return response()->json(['message' => 'Already marked as received'], 403);
        }

        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->picture, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

        $order->fill([
            'order_status' => 'Received',
            'delivery_payment_status' => 'Pending',
            'file_name' => $result->getPublicId(),
            'file_path' => $result->getPath()
        ])->save();

        $subject = 'Certificate Order Notification';
        $emailMessage = 'Your order #'.$order->id. ' has been successfully delivered by our biker.';
        $smsMessage = 'Your order #'.$order->id. ' has been successfully delivered by our biker.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
        // send sms and email notification to the person who orders it

        dispatch(
            new SendSingleNotificationJob(
                $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                $emailMessage, $order->id,  "App\Models\Order"
        ));

        dispatch(new OrderJob($order, $subject, $emailMessage));
        dispatch(new SMSJob($order->phone_no, $smsMessage));
        return response()->json(['message' => 'Order marked as received.'], 200);
    }

    // put mark as did not receive
    public function confirmDNROrder(Order $order) {
        activity()->disableLogging();
        if ($order->application_status != 'Approved' && $order->pick_up_type != 'Delivery' && $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        if ($order->order_status == 'Received') {
            return response()->json(['message' => 'Already marked as received'], 403);
        }

        $order->fill([
            'order_status' => 'DNR',
            'is_returned' => 'No',
        ])->save();

        $subject = 'Certificate Order Notification';
        $emailMessage = 'Your order #'.$order->id. ' has been marked as DNR (Did not receive by specified person on the order). It means that you didn\'t receive the order.';
        $smsMessage = 'Your order #'.$order->id. ' has been marked as DNR (Did not receive by specified person on the order). It means that you didn\'t receive the order.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
        // send app, sms and email notification to the person who orders it

        dispatch(
            new SendSingleNotificationJob(
                $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                $emailMessage, $order->id,  "App\Models\Order"
        ));

        dispatch(new OrderJob($order, $subject, $emailMessage));
        dispatch(new SMSJob($order->phone_no, $smsMessage));
        return response()->json(['message' => 'Order marked as DNR.'], 200);

    }

}
