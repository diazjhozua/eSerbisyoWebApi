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
    
    public function report($date_start,  $date_end, $sort_column, $sort_option, $status) {

        $title = 'Report - No data';
        $description = 'No data';
        try {
            $orderReports = OrderReport::with('user')
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($status) {
                    if($status == 'all') {
                        return null;
                    } else {
                        return $query->where('status', '=', $status);
                    }
                })
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($orderReports->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        // $reportsData = null;

        // $reportsData =  DB::table('order_Reports')
        //     ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
        //     ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        //     ->where('created_at', '>=', $date_start)
        //     ->where('created_at', '<=', $date_end)
        //     ->where(function($query) use ($status) {
        //         if($status == 'all') {
        //             return null;
        //         } else {
        //             return $query->where('status', '=', $status);
        //         }
        //     })
        //     ->first();


        $title = 'Order Reports';
        $modelName = 'Order';

        return view('admin.certification.pdf.orderreports', compact('title', 'modelName', 'orderReports', 
            'date_start', 'date_end', 'sort_column', 'sort_option','status'
        ));
    }

}
