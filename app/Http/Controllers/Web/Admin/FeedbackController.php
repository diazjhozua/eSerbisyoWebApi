<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RespondFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
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
        return view('admin.feedbacks.index')->with('feedbacks', $feedbacks)->with('feedbacksData', $feedbacksData);
    }

    public function respondReport(RespondFeedbackRequest $request, Feedback $feedback) {
        if ($feedback->status === 'Noted' || $feedback->status === 'Ignored') { return response()->json(Helper::instance()->alreadyNoted('feedback')); }
        $feedback->fill(['admin_respond' => $request->admin_respond,'status' => 'Noted'])->save();
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->noted('feedback'));
    }
}
