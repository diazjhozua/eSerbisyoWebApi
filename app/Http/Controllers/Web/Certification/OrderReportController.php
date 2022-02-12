<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\RespondFeedbackRequest;
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
        if ($orderReport->status === 'Noted') { return response()->json(Helper::instance()->alreadyNoted('orderReport'), 403); }
        $orderReport->fill(['admin_message' => $request->admin_respond, 'status' => 'Noted'])->save();
        return response()->json(['message' => "Responded successfuly. Email notification has been sent to the owner of this report"]);
    }

}
