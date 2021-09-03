<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use App\Http\Resources\FeedbackResource;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        // return all missing person report (from latest to old)
        $feedbacks = Feedback::with('user','feedback_type')->orderBy('created_at','desc')->get();

        return response()->json([
            'success' => true,
            'feedbacks' => FeedbackResource::collection($feedbacks)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackRequest $request)
    {
        $feedback = Feedback::where('user_id', 2)->orderBy('created_at', 'desc')->first();
        if (date('Y-m-d') == date('Y-m-d', strtotime($feedback->created_at))) {
            return response()->json([
                'success' => false,
                'message' => 'You already submitted a feedback in this day, please comeback tomorrow',
            ]);
        }

        $feedback = new Feedback;
        // $feedback->user_id = Auth::user()->id;
        $feedback->user_id = 2;
        $feedback->is_anonymous = $request->is_anonymous;
        $feedback->feedback_type_id = $request->feedback_type_id;
        $feedback->message = $request->message;
        $feedback->save();

        return response()->json([
            'success' => true,
            'message' => 'New feedback created succesfully',
            'feedback' => new FeedbackResource($feedback)
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeedbackRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
