<?php

namespace App\Http\Controllers\Api;

use App\Events\InquiryEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('user')->where('user_id', auth('api')->user()->id)->orderBy('created_at','desc')->get();
        return InquiryResource::collection($inquiries);
    }

    public function store(InquiryRequest $request)
    {
        $authInquiryCount = Inquiry::whereDate('created_at', Carbon::today())->where('user_id', auth('api')->user()->id)->count();

        if ($authInquiryCount > 3) {
            return response()->json(["message" => "You have already submitted to many inquiries within this day, please comeback tommorow to submit another inquiry"], 403);
        }

        $inquiry = Inquiry::create(array_merge($request->validated(), ['status' => 'Pending','user_id' => auth('api')->user()->id]));
        event(new InquiryEvent($inquiry->load('user')));
        return (new InquiryResource($inquiry->load('user')))->additional(Helper::instance()->storeSuccess('inquiry'));
    }


}
