<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderReportRequest;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\OrderResource;
use App\Models\Certificate;
use App\Models\CertificateForm;
use App\Models\CertificateOrder;
use App\Models\Order;
use App\Models\OrderReport;
use App\Models\UserRequirement;
use DB;
use Helper;
use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
{

    public function certificates()
    {
        // list of certicates
        $certificates = Certificate::with('requirements')->withCount('requirements')->get();
        return CertificateResource::collection($certificates);
    }

    public function index()
    {
        // authenticated user orders list
        $orders = Order::where('ordered_by', auth('api')->user()->id)->where('pick_up_type', '!=', 'Walkin')->orderBy('created_at','DESC')->get();
        return response()->json(["data" => $orders], 200);
    }

    public function create($pickupType)
    {
        // get available certificate depending on the pickup type
        if ( $pickupType == "Delivery") {
            $certificates = Certificate::where('status', 'Available')->where('is_open_delivery', '=', 1)->get();
        } else {
            $certificates = Certificate::where('status', 'Available')->get();
        }

        return response()->json(["data" => $certificates], 200);
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

    public function store(OrderRequest $request)
    {
        activity()->disableLogging();
        $isCompleteFormFields = $this->checkCertificateFormFields($request);

        if (Order::where('ordered_by', auth('api')->user()->id)->where('application_status', 'Pending')->count() >= 1) {
            return response()->json(['message' => 'You have an existing request, please wait for the transaction to be complete to request another orders'], 403);
        }

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
                    'delivery_fee' => ($request->pick_up_type == "Delivery") ? 60 : 0,
                    'location_address' => $request->location_address,
                    'application_status' => 'Pending',
                    'ordered_by' => auth('api')->user()->id,
                    'user_long' => ($request->pick_up_type == "Delivery") ? $request->longitude : null,
                    'user_lat' => ($request->pick_up_type == "Delivery") ? $request->latitude : null,
                    'pick_up_type' =>  $request->pick_up_type,
                    'order_status' => 'Waiting',
                ]);

                $totalPrice = 0;
                foreach ($request->certificate_forms as $key => $value) {
                    $certificateID = $value['certificate_id'];
                    $certificate = Certificate::with('requirements')->findOrFail($certificateID);

                    // check if the certificate is available
                    if ($certificate->status == 'Unavailable') {
                        return response()->json(['message' => 'The certificate '. $certificate->name. ' that you are requesting is currently unavailable right now'], 403);
                    }

                    if ($request->pick_up_type == "Delivery") {
                        if ($certificate->is_open_delivery == 0) {
                            return response()->json(['message' => 'The certificate '. $certificate->name. ' that you are requesting not available for delivery'], 403);
                        }
                    }

                    // check first if the auth user have complete requirements in the system
                    $user_requirements = UserRequirement::where('user_id', $order->ordered_by)->get();

                    foreach ($certificate->requirements as $requirement) {
                        if(!$user_requirements->contains('requirement_id', $requirement->id)) {
                            return response()->json([
                                'message' => "You don't have enough requirements to request this certificate. Go to the certificates section to see the required requirements",
                            ], 400);
                        }
                    }

                    //  end of checking the requirements

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
                                'status' => 'Pending',
                            ]);
                            break;
                        case 2:
                            // Barangay Cedula
                            $certificateForm = CertificateForm::create([
                                'certificate_id' => $certificateID,
                                'price_filled' => $certificate->price,
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
                                'status' => 'Pending',
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
                                'status' => 'Pending',
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
                                'status' => 'Pending',
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
                                'status' => 'Pending',
                            ]);
                            break;
                    }

                    CertificateOrder::create([
                        'order_id' => $order->id,
                        'certificate_form_id' => $certificateForm->id,
                    ]);
                }

                $order->fill(['total_price' => $totalPrice])->save();
                event(new OrderEvent($order->loadCount('certificateForms')));
                return response()->json(["data" => $order], 200);

                // return (new OrderResource($order))->additional(Helper::instance()->storeSuccess('order'));
            });
        }
    }

    public function show(Order $order)
    {
        activity()->disableLogging();
        if ($order->ordered_by != auth('api')->user()->id) {
            return response()->json(["message" => "You can only view your submitted order."], 403);
        }
        return response()->json(["data" => $order->load('contact', 'biker', 'certificateForms', 'orderReports')], 200);
    }


    public function submitReport(OrderReportRequest $request, Order $order) {
        activity()->disableLogging();
        if ($order->ordered_by != auth('api')->user()->id && $order->delivered_by != auth('api')->user()->id) {
            return response()->json(["message" => "You can only submit report in your transaction."], 403);
        }

        if (OrderReport::where('order_id', $order->id)->where('user_id', auth('api')->user()->id)->exists()) {
            return response()->json(["message" => "You have already submitted in this report."], 403);
        }

        OrderReport::create([
            'user_id' => auth('api')->user()->id,
            'order_id' => $order->id,
            'body' => $request->body,
            'status' => "Pending",
        ]);

        return response()->json(["message" => "Report submitted successfully."], 200);
    }

    public function destroy(Order $order)
    {
        //
    }
}
