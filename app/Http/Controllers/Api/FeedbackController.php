<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;
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
    public function store(Request $request)
    {
        // check if the user already created a feedback within this day
        // $feedback = Feedback::where('user_id', 2)->orderBy('created_at', 'desc')->first();

        // if (date('Y-m-d') == date('Y-m-d', strtotime($feedback->created_at))) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You already submitted a feedback in this day, please comeback tomorrow',
        //     ]);
        // }

        $rules = array(
            'is_anonymous' => 'required|integer|digits_between: 0,1',
            'feedback_type_id' => 'required|exists:feedback_types,id',
            'message' => 'required|string|min:5|max:255',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $feedback = new Feedback;
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
    public function update(Request $request, $id)
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
