<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RespondFeedbackRequest;
use App\Http\Resources\InquiryResource;
use App\Jobs\SendMailJob;
use App\Models\Inquiry;
use DB;
use Helper;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $first_date = date('Y-m-d',strtotime('first day of this month'));
        $last_date = date('Y-m-d',strtotime('last day of this month'));

        $inquiriesData =  DB::table('feedbacks')
        ->selectRaw('count(*) as inquiries_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
        ->first();

        $inquiries = Inquiry::with('user')->orderBy('created_at','desc')->get();
        return view('admin.information.inquiries.index')->with('inquiries', $inquiries)->with('inquiriesData', $inquiriesData);
    }

    public function respondReport(RespondFeedbackRequest $request, Inquiry $inquiry) {
        if ($inquiry->status === 'Noted' || $inquiry->status === 'Ignored') { return response()->json(Helper::instance()->alreadyNoted('Inquiry'), 403); }
        $inquiry->fill(['admin_message' => $request->admin_respond,'status' => 'Noted'])->save();
        $label1 = 'Your inquiry #'.$inquiry->id. ' has been noted by the administrator. Please see the message below and Thankyou for using the application <br> <br>';
        $label2 = 'Admin Message: '. $inquiry->admin_message;
        $message = $label1.$label2;
        dispatch(new SendMailJob($inquiry->user->email, "Inquiry #".$inquiry->id." update", $message));
        return (new InquiryResource($inquiry->load('user')))->additional(Helper::instance()->noted('Inquiry'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $status_option) {

        $title = 'Report - No data';
        $description = 'No data';

        try {
         $inquiries = Inquiry::with('user')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->where(function($query) use ($status_option) {
                if($status_option == 'all') {
                    return null;
                } else {
                    return $query->where('status', '=', ucwords($status_option));
                }
            })->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        $first_date = date('Y-m-d',strtotime('first day of this month'));
        $last_date = date('Y-m-d',strtotime('last day of this month'));

        $inquiriesData =  DB::table('inquiries')
            ->selectRaw('count(*) as inquiries_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($status_option) {
                if($status_option == 'all') {
                    return null;
                } else {
                    return $query->where('status', '=', ucwords($status_option));
                }
            })->first();

            $title = 'Inquiries Reports';
            $modelName = 'Inquiry';

         return view('admin.information.pdf.inquiry', compact('title', 'modelName', 'inquiries', 'inquiriesData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'status_option'
        ));

    }
}
