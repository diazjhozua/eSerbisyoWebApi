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
        $feedbacks = Feedback::with('type')->where('user_id', auth('api')->user()->id)->orderBy('id','desc')->get();
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
        $monthAvg = number_format(Feedback::where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))->avg('rating'), 2, '.', '');

        $yearAvg = number_format(Feedback::where('created_at', '>=', date('Y-m-d',strtotime('first day of this year')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this year')))->avg('rating'), 2, '.', '');
        $overall = number_format(Feedback::avg('rating'), 2, '.', '');

        $feedbackTypes = Type::withCount(['feedbacks' => function($query){
            $query->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
            ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')));
        }])->where('model_type', 'Feedback')->orderBy('feedbacks_count', 'DESC')->get();

        $trendingFeedbacks = Type::where('model_type', 'Feedback')->withAvg('feedbacks', 'rating')->withCount('feedbacks')->orderBy('feedbacks_count', 'DESC')->limit(5)->get();

        return response()->json([
            'monthAvg' => $monthAvg,
            'yearAvg' => $yearAvg,
            'overall' => $overall,
            'feedbackTypes' => $feedbackTypes,
            'trendingFeedbacks' => $trendingFeedbacks
        ], 200);

    }
}
