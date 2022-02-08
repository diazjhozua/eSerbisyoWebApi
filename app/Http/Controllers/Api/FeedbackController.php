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
        if (Feedback::whereDate('created_at', Carbon::today())->where('user_id', auth('api')->user()->id)->exists()) {
            return response()->json(["message" => "You have already submitted feedback within this day, please comeback tommorow to submit another feedback"], 403);
        }
        $feedback = Feedback::create(array_merge($request->validated(), ['status' => 'Pending','user_id' => auth('api')->user()->id]));
        event(new FeedbackEvent($feedback->load('type')));
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->storeSuccess('feedback'));
    }
}
