<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BikerApplicationRequest;
use App\Http\Requests\Api\PictureRequest;
use App\Jobs\OrderJob;
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
        $fileName = uniqid().time().'.jpg';
        $filePath = 'bikers/'.$fileName;
        Storage::disk('public')->put($filePath, base64_decode($request->picture));

        $bikerVerification = BikerRequest::create(array_merge($request->getData(),
            [
            'user_id' => auth('api')->user()->id,
            'credential_name' => $fileName, 'credential_file_path' => $filePath
            ]
        ));

        return response()->json(['data' => $bikerVerification], 201);
    }

    // get Auth delivery transaction
    public function getAuthTransaction() {
        $orders = Order::where('delivered_by', auth('api')->user()->id)->get();
        return response()->json(['data' => $orders], 200);
    }

    // get the list of approved delivery order request without any booked biker
    public function getListOrders() {
        $orders = Order::where('application_status', 'Approved')->where('pick_up_type', 'Delivery')->where('delivered_by', null)->get();
        return response()->json(['data' => $orders], 200);
    }

    // get Order Details
    public function getOrderDetails(Order $order) {
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }
        return response()->json(['data' => $order->load('certificateForms')], 200);
    }

    // put booked the order
    public function bookedOrder(Order $order) {
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != null) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        $order->fill([
            'delivered_by' => auth('api')->user()->id,
            'order_status' => 'Accepted'
        ])->save();

        $subject = 'Certificate Order Notification';
        $message = 'Your order #'.$order->id. ' has been booked by our biker delivery Please prepare the exact payment.';
        // send sms and email notification to the person who orders it
        dispatch(new OrderJob($order, $subject, $message));

        return response()->json(['message' => 'Order has been selected successfully'], 200);
    }

    // start riding biker
    public function startRiding(Order $order) {
        if ($order->application_status != 'Approved' || $order->pick_up_type != 'Delivery' || $order->delivered_by != auth('api')->user()->id) {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        $order->fill([
            'order_status' => 'On-Going'
        ])->save();

        $subject = 'Certificate Order Notification';
        $message = 'Your order #'.$order->id. ' has been start delivering your requested order by the biker. Please prepare the exact payment.';
        // send sms and email notification to the person who orders it
        dispatch(new OrderJob($order, $subject, $message));

        return response()->json(['message' => 'Notification has been set to the user.'], 200);
    }

    // put confirm receive order
    public function confirmReceiveOrder(PictureRequest $request, Order $order) {
        if ($order->application_status != 'Approved' && $order->pick_up_type != 'Delivery') {
            return response()->json(['message' => 'This order does not meet the requirements to view or book this order'], 403);
        }

        if ($order->order_status == 'Received') {
            return response()->json(['message' => 'Already marked as received'], 403);
        }

        $fileName = uniqid().time().'.jpg';
        $filePath = 'orders/'.$fileName;
        Storage::disk('public')->put($filePath, base64_decode($request->picture));

        $order->fill([
            'order_status' => 'Received',
            'file_name' => $fileName,
            'file_path' => $filePath
        ])->save();

        $subject = 'Certificate Order Notification';
        $message = 'Your order #'.$order->id. ' has been successfully delivered by our biker.';
        // send sms and email notification to the person who orders it
        dispatch(new OrderJob($order, $subject, $message));
        return response()->json(['message' => 'Order marked as received.'], 200);
    }

}
