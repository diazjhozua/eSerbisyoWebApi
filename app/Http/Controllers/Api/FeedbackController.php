<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\FeedbackTypeResource;
use App\Models\Feedback;
use App\Models\Type;
use App\Models\User;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('type')->orderBy('created_at','desc')->get();
        return FeedbackResource::collection($feedbacks)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Feedback')->get();
        return ['types' => FeedbackTypeResource::collection($types), 'success' => true] ;
    }

    public function store(FeedbackRequest $request)
    {
        $feedback = Feedback::create(array_merge($request->validated(), ['status' => 'Pending','user_id' => 546]));
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->storeSuccess('feedback'));
    }

    public function show(Feedback $feedback)
    {
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->itemFound('feedback'));
    }

    public function noted(Feedback $feedback) {
        if ($feedback->status === 'Noted' || $feedback->status === 'Ignored') { return response()->json(Helper::instance()->alreadyNoted('feedback')); }
        $feedback->fill(['status' => 'Noted'])->save();
        return (new FeedbackResource($feedback->load('type')))->additional(Helper::instance()->noted('feedback'));
    }
}
