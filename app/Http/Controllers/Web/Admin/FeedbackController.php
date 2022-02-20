<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\FeedbackReportRequest;
use App\Http\Requests\RespondFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Jobs\FeedbackJob;
use App\Models\Feedback;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index() {

        $first_date = date('Y-m-d',strtotime('first day of this month'));
        $last_date = date('Y-m-d',strtotime('last day of this month'));

        $feedbacksData =  DB::table('feedbacks')
        ->selectRaw('count(*) as feedbacks_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
        ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
        ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
        ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
        ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
        ->where('created_at', '>=', $first_date)
        ->where('created_at', '<=', $last_date)
        ->first();

        $feedbacks = Feedback::with('type')->orderBy('updated_at','desc')->get();
        return view('admin.information.feedbacks.index')->with('feedbacks', $feedbacks)->with('feedbacksData', $feedbacksData);
    }

    public function respondReport(RespondFeedbackRequest $request, Feedback $feedback) {
        if ($feedback->status === 'Noted' || $feedback->status === 'Ignored') { return response()->json(Helper::instance()->alreadyNoted('feedback'), 403); }
        $feedback->fill(['admin_respond' => $request->admin_respond,'status' => 'Noted'])->save();
        dispatch(new FeedbackJob($feedback));
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->noted('feedback'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $polarity_option, $status_option) {

        $title = 'Report - No data';
        $description = 'No data';
        try {

         $feedbacks = Feedback::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->where(function($query) use ($polarity_option, $status_option) {
                if($polarity_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($polarity_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($polarity_option != 'all' && $status_option == 'all') {
                    return $query->where('polarity', '=', ucwords($polarity_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('polarity', '=', ucwords($polarity_option));
                }
            })->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($feedbacks->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $feedbacks = null;
        $first_date = date('Y-m-d',strtotime('first day of this month'));
        $last_date = date('Y-m-d',strtotime('last day of this month'));


        $feedbacksData =  DB::table('feedbacks')
            ->selectRaw('count(*) as feedbacks_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($polarity_option, $status_option) {
                if($polarity_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($polarity_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($polarity_option != 'all' && $status_option == 'all') {
                    return $query->where('polarity', '=', ucwords($polarity_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('polarity', '=', ucwords($polarity_option));
                }
            })->first();


            $title = 'Feedback Reports';
            $modelName = 'Feedback';


         return view('admin.information.pdf.feedbackreport', compact('title', 'modelName', 'feedbacks', 'feedbacksData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'polarity_option', 'status_option'
        ));

    }

}
