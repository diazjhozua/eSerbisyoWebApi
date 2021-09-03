<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LostAndFoundRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\LostAndFoundResource;
use App\Models\LostAndFound;



class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function approved($id) {
        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);
            $lost_and_found->status = 2;
            $lost_and_found->save();

            return response()->json([
                'success' => true,
                'message' => 'Lost and found report is approved successfully',
                'lost_and_found' => new LostAndFoundResource($lost_and_found)
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }

    public function denied($id) {
        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);
            $lost_and_found->status = 3;
            $lost_and_found->save();

            return response()->json([
                'success' => true,
                'message' => 'Lost and found report is denied successfully',
                'lost_and_found' => new LostAndFoundResource($lost_and_found)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }

    public function resolved($id) {

        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);
            $lost_and_found->status = 4;
            $lost_and_found->save();

            return response()->json([
                'success' => true,
                'message' => 'Lost and found report is resolved successfully',
                'lost_and_found' => new LostAndFoundResource($lost_and_found)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }

    public function index()
    {
        // if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
        //     // if admin or staff
        //     $lost_and_founds = LostAndFound::with('user')->orderBy('created_at','DESC')->get();

        // } else {
        //     // if resident 2- for approved reports
        //     $lost_and_founds = LostAndFound::with('user')->where('status', 2)->orderBy('created_at','DESC')->get();
        // }

        $lost_and_founds = LostAndFound::with('user')->orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'lost_and_founds' => LostAndFoundResource::collection($lost_and_founds)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $report_types = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];

        return response()->json([
            'success' => true,
            'report_types' => $report_types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LostAndFoundRequest $request)
    {
        // check if the user already created a lost and found report within this day
        // $lost_and_found = LostAndFound::where('user_id', 2)->orderBy('created_at', 'desc')->first();

        // if (date('Y-m-d') == date('Y-m-d', strtotime($lost_and_found->created_at))) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You already submitted a missing-report in this day, please comeback tomorrow',
        //     ]);
        // }

        $lost_and_found = new LostAndFound();
        $lost_and_found->item = $request->item;
        $lost_and_found->last_seen = $request->last_seen;
        $lost_and_found->description = $request->description;
        $lost_and_found->contact_information = $request->contact_information;
        $lost_and_found->report_type = $request->report_type;

        // $lost_and_found->user_id = Auth::user()->id;
        $lost_and_found->user_id = 2;

        $lost_and_found->status = 1;

        // if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
        //     // if admin or staff the application would be automatic approved (2 - For Approved)
        //     $lost_and_found->status = 2;
        // } else {
        //     // if resident the application would be for approval (1 - For Approval)
        //     $lost_and_found->status = 1;
        // }

        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
        $lost_and_found->picture_name = $fileName;
        $lost_and_found->file_path = $filePath;

        $lost_and_found->save();

        return response()->json([
            'success' => true,
            'message' => 'New lost and found report created succesfully',
            'document' => new LostAndFoundResource($lost_and_found->load('user'))
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
        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found lost and found report data',
                'lost_and_found' => new LostAndFoundResource($lost_and_found)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);
            $report_types = [ (object)[ "id" => 1, "type" => "Missing"] , (object) ["id" => 2,"type" => "Found"] ];

            return response()->json([
                'success' => true,
                'message' => 'Found lost and found report data',
                'lost_and_found' => new LostAndFoundResource($lost_and_found),
                'report_types' => $report_types
            ]);

            //check first if the user who logs in is the creator of that missing report or part of the information staff
            // if ($lost_and_found->user_id == Auth::user()->id || Auth::user()->user_role_id == 1 ||
            // Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
            //     if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
            //         $status_list = [ (object)[ "id" => 1, "type" => "For Approval"] , (object) ["id" => 2,"type" => "Approved"], (object) ["id" => 3,"type" => "Denied"], (object) ["id" => 4,"type" => "Resolved"] ];
            //         return response()->json([
            //             'success' => true,
            //             'message' => 'Found lost and found report data',
            //             'lost_and_found' => new LostAndFoundResource($lost_and_found),
            //             'report_types' => $report_types,
            //             'status_list' => $status_list,
            //         ]);
            //     } else {
            //         return response()->json([
            //             'success' => true,
            //             'message' => 'Found lost and found report data',
            //             'lost_and_found' => new LostAndFoundResource($lost_and_found),
            //             'report_types' => $report_types,
            //         ]);
            //     }

            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Found lost and found data',
            //         'lost_and_found' => new LostAndFoundResource($lost_and_found)
            //     ]);
            // } else {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You don\'t have the priviledges to edit this data',
            //     ]);
            // }

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LostAndFoundRequest $request, $id)
    {
        try {
            $lost_and_found = LostAndFound::with('user')->findOrFail($id);

            //check first if the user who logs in is the creator of that missing report or part of the information staff
            // if ($lost_and_found->user_id !== Auth::user()->id || Auth::user()->user_role_id !== 1 ||
            // Auth::user()->user_role_id !== 2 || Auth::user()->user_role_id !== 4) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You don\'t have the priviledges to update this data',
            //     ]);
            // }

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }

        $lost_and_found->item = $request->item;
        $lost_and_found->last_seen = $request->last_seen;
        $lost_and_found->description = $request->description;
        $lost_and_found->contact_information = $request->contact_information;
        $lost_and_found->report_type = $request->report_type;

        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $lost_and_found->picture_name);

            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');

            $lost_and_found->picture_name = $fileName;
            $lost_and_found->file_path = $filePath;
        }

        $lost_and_found->status = 1;

        // if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
        //     $lost_and_found->status = $request->status;
        // } else {
        //     // if the user is resident, the missing report if approved already would become for approval
        //     $lost_and_found->status = 1;
        // }

        $lost_and_found->save();

        return response()->json([
            'success' => true,
            'message' => 'The lost and found report is successfully updated',
            'lost_and_found' => new LostAndFoundResource($lost_and_found)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $lost_and_found = LostAndFound::findOrFail($id);

            Storage::delete('public/missing-pictures/'. $lost_and_found->picture_name);
            $lost_and_found->delete();

            return response()->json([
                'success' => true,
                'message' =>  'The lost and found report is successfully deleted',
            ]);

            // if ($lost_and_found->user_id === Auth::user()->id || Auth::user()->user_role_id === 1 ||
            // Auth::user()->user_role_id === 2 || Auth::user()->user_role_id === 4) {
            //     Storage::delete('public/missing_persons/'. $lost_and_found->picture_name);
            //     $lost_and_found->delete();

            //     return response()->json([
            //         'success' => true,
            //         'message' =>  'The lost and found report is successfully deleted',
            //     ]);
            // } else {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You don\'t have the priviledges to delete this data',
            //     ]);
            // }

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('lost and found report'));
        }
    }
}
