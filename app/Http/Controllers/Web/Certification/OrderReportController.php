<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\RespondFeedbackRequest;
use App\Jobs\SendMailJob;
use App\Models\OrderReport;
use Helper;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    // display all order reports
    public function index() {
        $orderReports = OrderReport::with('user')->orderBy('created_at', 'DESC')->get();
        return view('admin.certification.orderReports.index', compact('orderReports'));
    }

    //
    public function respond(RespondFeedbackRequest $request,  OrderReport $orderReport) {
        $subject = 'Certificate Order Report Notification';
        if ($orderReport->status === 'Noted') { return response()->json(Helper::instance()->alreadyNoted('orderReport'), 403); }
        $orderReport->fill(['admin_message' => $request->admin_respond, 'status' => 'Noted'])->save();
        $label1 = 'Your report submitted in the order #'.$orderReport->order_id. ' has been noted by the administrator. Thankyou for using this application. <br> <br>';
        $label2 = '<strong>Admin Message:</strong> '. $orderReport->admin_message;
        $message = $label1.$label2;
        dispatch(new SendMailJob($orderReport->user->email, $subject, $message));
        return response()->json(['message' => "Responded successfuly. Email notification has been sent to the owner of this report"]);
    }

}
