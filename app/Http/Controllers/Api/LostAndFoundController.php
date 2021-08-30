<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public function index()
    {
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
        // $lost_and_found = LostAndFound::where('user_id', 2)->orderBy('created_at', 'desc')->first();

        // if (date('Y-m-d') == date('Y-m-d', strtotime($lost_and_found->created_at))) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You already submitted a missing-report in this day, please comeback tomorrow',
        //     ]);
        // }

        $rules = array(
            'item' => 'required|string|min:3|max:120',
            'last_seen' => 'required|string|min:3|max:120',
            'description' => 'required|string|min:3|max:120',
            'contact_information' => 'required|string|min:3|max:120',
            'is_found' => 'required|integer|digits_between: 0,1',
            'picture' => 'required|mimes:jpeg,png|max:3000',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $lost_and_found = new LostAndFound();
        $lost_and_found->item = $request->item;
        $lost_and_found->last_seen = $request->last_seen;
        $lost_and_found->description = $request->description;
        $lost_and_found->contact_information = $request->contact_information;
        $lost_and_found->is_found = $request->is_found;
        $lost_and_found->is_resolved = 0;
        $lost_and_found->is_approved = 1;

        // $missing_person->user_id = Auth::user()->id;
        $lost_and_found->user_id = 2;

        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');

        $lost_and_found->picture_name = $fileName;
        $lost_and_found->file_path = $filePath;

        $lost_and_found->is_resolved = 0;
        $lost_and_found->is_approved = 0;

        $lost_and_found->save();

        return response()->json([
            'success' => true,
            'message' => 'New lost and found item created succesfully',
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
                'message' => 'Found lost and found data',
                'lost_and_found' => new LostAndFoundResource($lost_and_found)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No lost and found id found',
            ]);
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

            return response()->json([
                'success' => true,
                'message' => 'Found lost and found data',
                'missing_person' => new LostAndFoundResource($lost_and_found)
            ]);

            //check first if the user who logs in is the creator of that missing report or part of the information staff
            if ($lost_and_found->user_id == Auth::user()->id || Auth::user()->user_role_id == 1 ||
            Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
                return response()->json([
                    'success' => true,
                    'message' => 'Found lost and found data',
                    'missing_person' => new LostAndFoundResource($lost_and_found)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You don\'t have the priviledges to edit this data',
                ]);
            }

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No lost and found id found',
            ]);
        }
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
        $lost_and_found = LostAndFound::with('user')->findOrFail($id);

        $rules = array(
            'item' => 'required|string|min:3|max:120',
            'last_seen' => 'required|string|min:3|max:120',
            'description' => 'required|string|min:3|max:120',
            'contact_information' => 'required|string|min:3|max:120',
            'is_found' => 'required|integer|digits_between: 0,1',
            'is_resolved' => 'integer|digits_between: 0,1',
            'is_approved' => 'integer|digits_between: 0,1',
            'picture' => 'mimes:jpeg,png|max:3000',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $lost_and_found->item = $request->item;
        $lost_and_found->last_seen = $request->last_seen;
        $lost_and_found->description = $request->description;
        $lost_and_found->contact_information = $request->contact_information;
        $lost_and_found->is_found = $request->is_found;
        $lost_and_found->is_resolved = isset($request->is_resolved) ? $request->is_resolved : $lost_and_found->is_resolved;
        $lost_and_found->is_approved =  isset($request->is_approved) ? $request->is_approved : $lost_and_found->is_approved;

        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $lost_and_found->picture_name);

            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');

            $lost_and_found->picture_name = $fileName;
            $lost_and_found->file_path = $filePath;
        }

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
            //         'message' =>  'The missing person is successfully deleted',
            //     ]);
            // } else {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You don\'t have the priviledges to edit this data',
            //     ]);
            // }

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No lost and found id found',
            ]);
        }
    }
}
