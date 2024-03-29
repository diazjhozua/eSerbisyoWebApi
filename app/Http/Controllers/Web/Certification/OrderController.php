<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderAdminRequest;
use App\Http\Requests\OrderApplicationAdminRequest;
use App\Http\Resources\OrderResource;
use App\Jobs\OrderJob;
use App\Jobs\OrderStatusJob;
use App\Jobs\SendMailJob;
use App\Jobs\SendSingleNotificationJob;
use App\Jobs\SMSJob;
use App\Models\Certificate;
use App\Models\CertificateForm;
use App\Models\CertificateOrder;
use App\Models\Order;
use App\Models\UserRequirement;
use DB;
use Helper;
use Log;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::withCount('certificateForms')->orderBy('id', 'DESC')->get();
        $pendingOrders = Order::where('application_status', 'Pending')->get();
        $unprocessedOrders= Order::where('pick_up_type', 'Delivery')->where('order_status', 'Received')->where('delivery_payment_status', 'Pending')->get();
        $returnableOrders = Order::where('pick_up_type', 'Delivery')->where('order_status', 'DNR')->where('is_returned', 'No')->get();
        return view('admin.certification.orders.index', compact('orders', 'pendingOrders', 'unprocessedOrders', 'returnableOrders'));
    }


    public function create()
    {
        $certificates = Certificate::all();
        return view('admin.certification.orders.create', compact('certificates'));
    }

    public function checkCertificateFormFields($request) {
        $isCompleteFormFields = true;

        foreach ($request->certificate_forms as $key => $value) {
            $certificateID = $value['certificate_id'];

            if ($certificateID != 5) {
                if (!isset($value['civil_status']) || !isset($value['birthday'])) {
                    $isCompleteFormFields = false;
                }
            }

            if ($certificateID != 5 || $certificateID != 4) {
                if (!isset($value['citizenship'])) {
                    $isCompleteFormFields = false;
                }
            }

            if ($certificateID == 2 || $certificateID == 4) {
                if (!isset($value['birthplace'])) {
                    $isCompleteFormFields = false;
                }
            }

            switch ($certificateID) {
                case 1:
                    // Barangay Indigency
                    if (!isset($value['purpose']) ) {

                        $isCompleteFormFields = false;
                    }

                    break;
                case 2:
                    // Barangay Cedula
                    if (!isset($value['profession']) || !isset($value['height']) || !isset($value['weight']) || !isset($value['sex']) ||
                        !isset($value['cedula_type']) || !isset($value['basic_tax'])
                    ) {
                        Log::debug($value['cedula_type']);
                        $isCompleteFormFields = false;
                    }
                    break;
                case 3:
                    // Barangay Clearance
                    if (!isset($value['purpose']) ) {
                        $isCompleteFormFields = false;
                    }
                    break;
                case 4:
                    // Barangay ID
                    if (!isset($value['contact_no']) || !isset($value['contact_person']) || !isset($value['contact_person_no']) || !isset($value['contact_person_relation'])) {
                        $isCompleteFormFields = false;
                    }
                    break;
                case 5:
                    // Barangay Business Permit
                    if (!isset($value['business_name']) ) {
                        $isCompleteFormFields = false;
                    }
                    break;
            }
        }

        return $isCompleteFormFields;
    }

    public function store(OrderAdminRequest $request)
    {
        $isCompleteFormFields = $this->checkCertificateFormFields($request);

        if ($isCompleteFormFields != true) {
            return response()->json([
                'message' => 'Please fill all the fields in certificate forms',
            ], 400);
        } else {
            // means complete all fields in all requirements
            return DB::transaction(function() use ($request) {
                $order = Order::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'location_address' => $request->location_address,
                    'pickup_date' => now(),
                    'received_at' => now(),
                    'application_status' => 'Approved',
                    'admin_message' => 'No need to respond (Walkin)',
                    'pick_up_type' => 'Walkin',
                    'order_status' => 'Received',
                ]);

                $totalPrice = 0;
                foreach ($request->certificate_forms as $key => $value) {

                    $certificateID = $value['certificate_id'];

                    $certificate = Certificate::findOrFail($certificateID);
                    $totalPrice = $totalPrice + $certificate->price;

                    switch ($certificateID) {
                        case 1:
                            // Barangay Indigency
                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
                                'first_name' => $value['first_name'],
                                'middle_name' =>  $value['middle_name'],
                                'last_name' =>  $value['last_name'],
                                'address' =>  $value['address'],
                                'birthday' =>  $value['birthday'],
                                'citizenship' =>  $value['citizenship'],
                                'civil_status' =>  $value['civil_status'],
                                'address' =>  $value['address'],
                                'purpose' =>  $value['purpose'],
                                'status' => 'Approved',
                            ]);
                            break;
                        case 2:
                            // Barangay Cedula
                            $totalTax = $value['basic_tax'] + $value['additional_tax'] + ($value['gross_receipt_preceding'] == null ? 0 : $value['gross_receipt_preceding']) + ($value['gross_receipt_profession'] == null ? 0 : $value['gross_receipt_profession']) + ($value['real_property'] == null ? 0 : $value['real_property']);
                            if ($value['interest'] == null ? 0 : $value['interest'] != 0) {
                                $interest = $totalTax * $value['interest']->interest;
                                $totalAmountTax = $totalTax + $interest;
                            } else {
                                $totalAmountTax = $totalTax;
                            }
                            $cedulaPrice = $certificate->price + $totalAmountTax;

                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $cedulaPrice,
                                'first_name' => $value['first_name'],
                                'middle_name' =>  $value['middle_name'],
                                'last_name' =>  $value['last_name'],
                                'birthday' =>  $value['birthday'],
                                'address' =>  $value['address'],
                                'citizenship' =>  $value['citizenship'],
                                'birthplace' => $value['birthplace'],
                                'tin_no' => $value['tin_no'] == null ? null : $value['tin_no'],
                                'icr_no' => $value['icr_no'] == null ? null : $value['icr_no'],
                                'civil_status' => $value['civil_status'],
                                'sex' => $value['sex'],
                                'cedula_type' => $value['cedula_type'],
                                'height' => $value['height'],
                                'weight' => $value['weight'],
                                'profession' => $value['profession'],
                                'basic_tax' => $value['basic_tax'],
                                'additional_tax' => $value['additional_tax']== null ? 0 : $value['additional_tax'],
                                'gross_receipt_preceding' => $value['gross_receipt_preceding'] == null ? 0 : $value['gross_receipt_preceding'],
                                'gross_receipt_profession' => $value['gross_receipt_profession'] == null ? 0 : $value['gross_receipt_profession'],
                                'real_property' => $value['real_property'] == null ? 0 : $value['real_property'],
                                'interest' => $value['interest'] == null ? 0 : $value['interest'],
                                'status' => 'Approved',
                            ]);
                            break;

                        case 3:
                            // Barangay Clearance
                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
                                'first_name' => $value['first_name'],
                                'middle_name' =>  $value['middle_name'],
                                'last_name' =>  $value['last_name'],
                                'birthday' =>  $value['birthday'],
                                'citizenship' =>  $value['citizenship'],
                                'civil_status' =>  $value['civil_status'],
                                'address' =>  $value['address'],
                                'purpose' =>  $value['purpose'],
                                'status' => 'Approved',
                            ]);
                            break;
                        case 4:
                            // Barangay ID
                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
                                'first_name' => $value['first_name'],
                                'middle_name' =>  $value['middle_name'],
                                'last_name' =>  $value['last_name'],
                                'address' =>  $value['address'],
                                'contact_no' =>  $value['contact_no'],
                                'birthday' =>  $value['birthday'],
                                'birthplace' =>  $value['birthplace'],
                                'contact_person' =>  $value['contact_person'],
                                'contact_person_no' =>  $value['last_name'],
                                'contact_person_relation' =>  $value['last_name'],
                                'status' => 'Approved',
                            ]);
                            break;
                        case 5:
                            // Barangay Business Permit
                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
                                'first_name' => $value['first_name'],
                                'middle_name' =>  $value['middle_name'],
                                'last_name' =>  $value['last_name'],
                                'address' =>  $value['address'],
                                'business_name' =>  $value['business_name'],
                                'status' => 'Approved',
                            ]);
                            break;
                    }

                    CertificateOrder::insert([
                        'order_id' => $order->id,
                        'certificate_form_id' => $certificateForm->id,
                    ]);
                }

                $order->fill(['total_price' => $totalPrice])->save();

                return (new OrderResource($order))->additional(Helper::instance()->storeSuccess('order'));
            });
        }

    }

    public function show(Order $order)
    {
        $order = $order->load('certificateForms.certificate.requirements', 'contact.user_requirements');

        $user_requirements = UserRequirement::where('user_id', $order->ordered_by)->get();

        $noRequirements = collect();
        $passedRequirements = collect();
        $isCompleteRequirements = false;

        foreach ($order->certificateForms as $certificateForm) {
            foreach ($order->certificateForms as $certificateForm) {
                foreach ($certificateForm->certificate->requirements as $requirement) {
                    if($user_requirements->contains('requirement_id', $requirement->id)) {
                        if(!$passedRequirements->contains('requirement_id', $requirement->id)) {
                            $data = $user_requirements->where('requirement_id', $requirement->id)->first();
                            $passedRequirements->push(['id' => $data->id, 'requirement_id' => $requirement->id, 'name' => $requirement->name, 'file_name' => $data->file_name, 'file_path' => $data->file_path]);
                        }
                    } else {
                        if(!$noRequirements->contains('requirement_id', $requirement->id)) {
                            $noRequirements->push(['id' => $requirement->id, 'requirement_id' => $requirement->id, 'name' => $requirement->name]);
                        }
                    }
                }
            }
        }

        if ($noRequirements->isEmpty()) {
            $isCompleteRequirements = true;
        }

        $applicationType = [
            (object) ["id" => 2, "type" => "Approved"], (object) ["id" => 2, "type" => "Denied"],
        ];

        if ($order->pick_up_type == "Delivery") {
            $orderType = [
                (object) ["id" => 2, "type" => "On-Going"]
            ];
        } else {
            $orderType = [
                (object) ["id" => 2, "type" => "Received"],
                (object) ["id" => 2, "type" => "DNR"]
            ];
        }

        $deliveryPayments = [
            (object)[ "id" => 1, "type" => "Pending"], (object) ["id" => 2, "type" => "Received"],
        ];

        $isReturns = [
            (object)[ "id" => 1, "type" => "No"], (object) ["id" => 2, "type" => "Yes"],
        ];

        return view('admin.certification.orders.show', compact('order', 'applicationType', 'orderType', 'noRequirements', 'isCompleteRequirements', 'passedRequirements', 'deliveryPayments', 'isReturns'));
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(OrderAdminRequest $request, Order $order)
    {
        $subject = 'Certificate Order Notification';
        $message = null;
        $ids = $order->certificateForms()->pluck('id');
        if (isset($request->order_status)) {
            if ($order->pick_up_type == 'Delivery') {
                if ($request->order_status != 'On-Going') {
                    return response()->json(['message' => 'Invalid order status'], 403);
                }
                $order->fill(['order_status' => $request->order_status])->save();
                $message = 'Your order #'.$order->id. ' has been pickup by our biker delivery Please prepare the exact payment.';
                $smsMessage = 'Your order #'.$order->id. ' has been pickup by our biker delivery Please prepare the exact payment.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            } else {
                if ($request->order_status == 'Received') {
                    $order->fill(['order_status' => $request->order_status, 'received_at' => now()])->save();
                    $message = 'Your order #'.$order->id. ' has been marked by our administrator that you received your requested certificates.';
                    $smsMessage = 'Your order #'.$order->id. ' has been marked by our administrator that you received your requested certificates.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
                    foreach($ids as $id) {
                        CertificateForm::where('id', $id )
                            ->update(['status' => 'Approved']);
                    }

                } elseif ($request->order_status == 'DNR') {
                    $order->fill(['order_status' => $request->order_status])->save();
                    $message = 'Your order #'.$order->id. ' has been marked by our administrator that you did not receive your requested certificates.';
                    $smsMessage = 'Your order #'.$order->id. ' has been marked by our administrator that you did not receive your requested certificates.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';

                    foreach($ids as $id) {
                        CertificateForm::where('id', $id )
                            ->update(['status' => 'Cancelled']);
                    }
                }
            }

            dispatch(
                new SendSingleNotificationJob(
                    $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                    $message, $order->id,  "App\Models\Order"
            ));

            dispatch(new OrderJob($order, $subject, $message));
            dispatch(new SMSJob($order->phone_no, $smsMessage));
        } elseif (isset($request->delivery_payment_status)) {
            $subject = 'Certificate Biker Order Notification';
            if ($request->delivery_payment_status == 'Pending') {
                $message = 'Your delivery order #'.$order->id. ' has been marked as Pending in delivery payment. Go to the barangay to process the payment.';
                $smsMessage = 'Your delivery order #'.$order->id. ' has been marked as Pending in delivery payment. Go to the barangay to process the payment.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            } else {
                $message = 'Your delivery order #'.$order->id. ' has been marked as Processed. It means that the transaction is completed. Thankyou for your service!';
                $smsMessage = 'Your delivery order #'.$order->id. ' has been marked as Processed. It means that the transaction is completed. Thankyou for your service!'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            }
            $order->fill(['delivery_payment_status' => $request->delivery_payment_status])->save();

            dispatch(
                new SendSingleNotificationJob(
                    $order->biker->device_id, $order->biker->id, "Certificate Biker Order Notification",
                    $message, $order->id,  "App\Models\BikerDelivery"
            ));

            dispatch(new SendMailJob($order->biker->email, $subject, $message));
            dispatch(new SMSJob($order->biker->phone_no, $smsMessage));
        } elseif(isset($request->is_returned)) {
            $subject = 'Certificate Biker Order Notification';
            if ($request->is_returned == 'No') {
                $message = 'Your delivery order #'.$order->id. ' has been marked that you still not  returned the delivery item. Go to the barangay to process the payment.';
                $smsMessage = 'Your delivery order #'.$order->id. ' has been marked that you still not  returned the delivery item. Go to the barangay to process the payment.'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            } else {
                $message = 'Your delivery order #'.$order->id. ' has been marked that you have returned the item properly. It means that the transaction is completed. Thankyou for your service!';
                $smsMessage = 'Your delivery order #'.$order->id. ' has been marked that you have returned the item properly. It means that the transaction is completed. Thankyou for your service!'.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            }
            $order->fill(['is_returned' => $request->is_returned])->save();


            dispatch(
                new SendSingleNotificationJob(
                    $order->biker->device_id, $order->biker->id, "Certificate Biker Order Notification",
                    $message, $order->id,  "App\Models\BikerDelivery"
            ));

            dispatch(new SendMailJob($order->biker->email, $subject, $message));
            dispatch(new SMSJob($order->biker->phone_no, $smsMessage));
        }

        return response()->json(['message' => 'Order status has been updated' ], 200);
    }

    // update order application
    public function updateApplicationStatus(OrderApplicationAdminRequest $request, Order $order)
    {

        if ($order->application_status != "Pending") {
            return response()->json(['message' => 'You can only verify pending request'], 403);
        }

        // update order
        $order->fill($request->getData())->save();

        // also update certificate forms in specified order.
        $ids = $order->certificateForms()->pluck('id');
        foreach($ids as $id) {
            CertificateForm::where('id', $id )
                ->update(['status' => $request->application_status]);
        }

        $subject = 'Certificate Order Notification';
        $message = null;
        $smsMessage = null;
        if ($request->application_status == "Approved") {
            $order->fill(['order_status' => 'Waiting'])->save();
            // for email
            $label1 = 'Your order #'.$order->id. ' has been approved by the administrator. Please expect to received the document (If Delivery) or go to the barangay office to pickup your order (If Pickup) at '. \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y'). ' working hours.';
            $label2 = 'If the order has not been selected by the biker or delivers after 3 days prior to the delivery, it will be marked as Cancelled. <br> <br> ';
            $label3 = 'Admin Message: '. $order->admin_message;
            $message = $label1.$label2.$label3;

            // for sms
            $smsLabel1 = 'Your order #'.$order->id. ' has been approved by the administrator. Please expect to received the document (If Delivery) or go to the barangay office to pickup your order (If Pickup) at '. \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y'). ' working hours.';
            $smsLabel2 = 'If the order has not been selected by the biker or delivers after 3 days prior to the delivery, it will be marked as Cancelled.';
            $smsLabel3 =  PHP_EOL.PHP_EOL.'Admin Message: '. $order->admin_message.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            $smsMessage = $smsLabel1.$smsLabel2.$smsLabel3;
        } else {
            // for email
            $label1 = 'Your order #'.$order->id. ' has been denied by the administrator. Please see below the reason message why it is not approved. <br> <br> ';
            $label2 = 'Reason: '. $order->admin_message;
            $message = $label1.$label2;

            // for sms
            $smsLabel1 = 'Your order #'.$order->id. ' has been denied by the administrator. Please see below the reason message why it is not approved.';
            $smsLabel2 = PHP_EOL.PHP_EOL.'Reason: '. $order->admin_message.PHP_EOL.PHP_EOL.'-Barangay Cupang';
            $smsMessage = $smsLabel1.$smsLabel2;
        }

        // send app, sms and email notification to the person who orders it
        dispatch(
            new SendSingleNotificationJob(
                $order->contact->device_id, $order->contact->id, "Certificate Order Notification",
                $smsLabel1, $order->id,  "App\Models\Order"
        ));

        dispatch(new OrderJob($order, $subject, $message));
        dispatch(new SMSJob($order->phone_no, $smsMessage));

        return response()->json(['message' => 'Order status has been updated' ], 200);
    }


    public function destroy(Order $order)
    {
        $order = $order->load('certificateForms.certificate.requirements', 'contact.user_requirements');
        $ids = $order->certificateForms()->pluck('id');
        CertificateForm::whereIn('id',$ids)->delete();
        $order->delete();

        return response()->json(Helper::instance()->destroySuccess('order'));
    }

    public function printReceipt(Order $order) {
        return view('admin.certification.orders.receipt', compact('order'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $pick_up_type, $order_status, $application_status) {

        $title = 'Report - No data';
        $description = 'No data';
        try {
            $orders = Order::withCount('certificateForms')->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($pick_up_type) {
                    if($pick_up_type == 'all') {
                        return null;
                    } else {
                        return $query->where('pick_up_type', '=', $pick_up_type);
                    }
                })
                ->where(function($query) use ($order_status) {
                    if($order_status == 'all') {
                        return null;
                    } else {
                        return $query->where('order_status', '=', $order_status);
                    }
                })
                ->where(function($query) use ($application_status) {
                    if($application_status == 'all') {
                        return null;
                    } else {
                        return $query->where('application_status', '=', $application_status);
                    }
                })
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($orders->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('orders')
            ->selectRaw('count(*) as orders_count')
            ->selectRaw('sum(total_price) as total_price')
            ->selectRaw('sum(delivery_fee) as delivery_fee')
            ->selectRaw("count(case when application_status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when application_status = 'Cancelled' then 1 end) as cancelled_count")
            ->selectRaw("count(case when application_status = 'Approved' then 1 end) as approved_count")
            ->selectRaw("count(case when application_status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when pick_up_type = 'Walkin' then 1 end) as walkin_count")
            ->selectRaw("count(case when pick_up_type = 'Pickup' then 1 end) as pickup_count")
            ->selectRaw("count(case when pick_up_type = 'Delivery' then 1 end) as delivery_count")
            ->selectRaw("count(case when order_status = 'Waiting' then 1 end) as waiting_count")
            ->selectRaw("count(case when order_status = 'Received' then 1 end) as received_count")
            ->selectRaw("count(case when order_status = 'DNR' then 1 end) as dnr_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($pick_up_type) {
                if($pick_up_type == 'all') {
                    return null;
                } else {
                    return $query->where('pick_up_type', '=', $pick_up_type);
                }
            })
            ->where(function($query) use ($order_status) {
                if($order_status == 'all') {
                    return null;
                } else {
                    return $query->where('order_status', '=', $order_status);
                }
            })
            ->where(function($query) use ($application_status) {
                if($application_status == 'all') {
                    return null;
                } else {
                    return $query->where('application_status', '=', $application_status);
                }
            })
            ->first();


        $title = 'Order Reports';
        $modelName = 'Order';

        return view('admin.certification.pdf.orders', compact('title', 'modelName', 'orders', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'pick_up_type', 'order_status', 'application_status',
        ));
    }


}
