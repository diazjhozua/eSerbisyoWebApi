<?php

namespace App\Http\Controllers\Api;

use App\Events\FeedbackEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\FeedbackTypeResource;
use App\Models\Feedback;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('type')->where('user_id', auth('api')->user()->id)->orderBy('created_at','desc')->get();
        return FeedbackResource::collection($feedbacks);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Feedback')->get();
        return response()->json(['types' => FeedbackTypeResource::collection($types)], 200);
    }

    public function store(FeedbackRequest $request)
    {
        activity()->disableLogging();
        if (Feedback::whereDate('created_at', Carbon::today())->where('user_id', auth('api')->user()->id)->exists()) {
            return response()->json(["message" => "You have already submitted feedback within this day, please comeback tommorow to submit another feedback"], 403);
        }

        $feedback = Feedback::create(array_merge($request->validated(), ['status' => 'Pending','user_id' => auth('api')->user()->id]));
        event(new FeedbackEvent($feedback->load('type')));
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->storeSuccess('feedback'));
    }

    // get short analytics about the overall overview.
    public function getAnalytics()
    {
        $feedbacksData = DB::table('feedbacks')
            ->selectRaw("count(*) as this_year_total_feedbacks ")
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as this_year_positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as this_year_neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as this_year_negative_count")
            ->whereRaw("created_at >= '". date('Y-m-d',strtotime('first day of this year')) ."' AND created_at <='".date('Y-m-d',strtotime('last day of this year'))."'")
            ->first();

        $feedbacksData->this_year_positive_count = $feedbacksData->this_year_total_feedbacks == 0  ? 0 : round(($feedbacksData->this_year_positive_count / $feedbacksData->this_year_total_feedbacks) * 100, 2);
        $feedbacksData->this_year_neutral_count = $feedbacksData->this_year_total_feedbacks == 0  ? 0 :  round(($feedbacksData->this_year_neutral_count / $feedbacksData->this_year_total_feedbacks) * 100, 2);
        $feedbacksData->this_year_negative_count = $feedbacksData->this_year_total_feedbacks == 0  ? 0 :  round(($feedbacksData->this_year_negative_count / $feedbacksData->this_year_total_feedbacks) * 100, 2);

        $trendingFeedbacks = Type::where('model_type', 'Feedback')->withCount('feedbacks')->orderBy('feedbacks_count', 'DESC')->limit(5)->get();
        return response()->json([
            'feedbacksThisYearOverview' => $feedbacksData,
            'trendingFeedbacks' => $trendingFeedbacks
        ], 200);
    }
}
