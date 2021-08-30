<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\MissingPersonResource;
use App\Models\MissingPerson;


class MissingPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $missing_persons = MissingPerson::with('user')->get();

        return response()->json([
            'success' => true,
            'missing_persons' => MissingPersonResource::collection($missing_persons)
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
        // $missing_person = MissingPerson::where('user_id', 2)->orderBy('created_at', 'desc')->first();

        // if (date('Y-m-d') == date('Y-m-d', strtotime($missing_person->created_at))) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You already submitted a missing-report in this day, please comeback tomorrow',
        //     ]);
        // }

        $rules = array(
            'name' => 'required|string|min:3|max:50',
            'height' => 'required|numeric|between:1,9.99',
            'weight' => 'required|numeric|between:1,120.99',
            'age' => 'required|integer|between:0,200',
            'eyes' => 'string|min:3|max:50',
            'hair' => 'string|min:3|max:50',
            'unique_sign' => 'required|string|min:3|max:120',
            'important_information' => 'required|string|min:3|max:120',
            'last_seen' => 'required|string|min:3|max:60',
            'contact_information' => 'required|string|min:3|max:120',
            'picture' => 'required|mimes:jpeg,png|max:3000'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $missing_person = new MissingPerson();
        $missing_person->name = $request->name;
        $missing_person->height = $request->height;
        $missing_person->weight = $request->weight;
        $missing_person->age = $request->age;
        $missing_person->eyes = $request->eyes;
        $missing_person->hair = $request->hair;
        $missing_person->unique_sign = $request->unique_sign;
        $missing_person->important_information = $request->important_information;
        $missing_person->last_seen = $request->last_seen;
        $missing_person->contact_information = $request->contact_information;

        // $missing_person->user_id = Auth::user()->id;
        $missing_person->user_id = 2;


        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');

        $missing_person->picture_name = $fileName;
        $missing_person->file_path = $filePath;

        $missing_person->is_resolved = 0;

        // make sure when the authentication has implemented
        // this is_approved will be 1 = true if the user who created belongs to the information staff or admin
        $missing_person->is_approved = 0;

        $missing_person->save();

        return response()->json([
            'success' => true,
            'message' => 'New missing person created succesfully',
            'document' => new MissingPersonResource($missing_person->load('user'))
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
            $missing_person = MissingPerson::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found missing person data',
                'missing_person' => new MissingPersonResource($missing_person)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No missing person found',
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
            $missing_person = MissingPerson::with('user')->findOrFail($id);

            //check first if the user who logs in is the creator of that missing report or part of the information staff
            if ($missing_person->user_id == Auth::user()->id || Auth::user()->user_role_id == 1 ||
            Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
                return response()->json([
                    'success' => true,
                    'message' => 'Found missing person data',
                    'missing_person' => new MissingPersonResource($missing_person)
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
                'message' => 'No missing person found',
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
        $missing_person = MissingPerson::with('user')->findOrFail($id);
        // try {
        //     $missing_person = MissingPerson::with('user')->findOrFail($id);

        //     //check first if the user who logs in is the creator of that missing report or part of the information staff
        //     if ($missing_person->user_id !== Auth::user()->id || Auth::user()->user_role_id !== 1 ||
        //     Auth::user()->user_role_id !== 2 || Auth::user()->user_role_id !== 4) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'You don\'t have the priviledges to edit this data',
        //         ]);
        //     }

        // } catch (ModelNotFoundException $ex){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'No missing person found',
        //     ]);
        // }

        $rules = array(
            'name' => 'required|string|min:3|max:50',
            'height' => 'required|numeric|between:1,9.99',
            'weight' => 'required|numeric|between:1,120.99',
            'age' => 'required|integer|between:0,200',
            'eyes' => 'string|min:3|max:50',
            'hair' => 'string|min:3|max:50',
            'unique_sign' => 'required|string|min:3|max:120',
            'important_information' => 'required|string|min:3|max:120',
            'last_seen' => 'required|string|min:3|max:60',
            'contact_information' => 'required|string|min:3|max:120',
            'picture' => 'mimes:jpeg,png|max:3000',
            'is_resolved' => 'integer|digits_between: 0,1',
            'is_approved' => 'integer|digits_between: 0,1',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $missing_person->name = $request->name;
        $missing_person->height = $request->height;
        $missing_person->weight = $request->weight;
        $missing_person->age = $request->age;
        $missing_person->eyes = $request->eyes;
        $missing_person->hair = $request->hair;
        $missing_person->unique_sign = $request->unique_sign;
        $missing_person->important_information = $request->important_information;
        $missing_person->last_seen = $request->last_seen;
        $missing_person->contact_information = $request->contact_information;

        // $missing_person->user_id = Auth::user()->id;
        $missing_person->user_id = 2;

        //check if they want to update the pdf file
        if($request->hasFile('picture')) {
            Storage::delete('public/missing-pictures/'. $missing_person->picture_name);

            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');

            $missing_person->picture_name = $fileName;
            $missing_person->file_path = $filePath;
        }

        $missing_person->is_approved = isset($request->is_approved) ? $request->is_approved : $missing_person->is_approved;
        $missing_person->is_resolved = isset($request->is_resolved) ? $request->is_resolved : $missing_person->is_resolved;

        // if (Auth::user()->user_role_id == 1 ||
        // Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
        //     $missing_person->is_approved = isset($request->is_approved) ? $request->is_approved : $missing_person->is_approved;
        //     $missing_person->is_resolved = isset($request->is_resolved) ? $request->is_resolved : $missing_person->is_resolved;
        // } else {
        //     // if the user is resident, the missing report if approved already would become false value
        //     $missing_person->is_approved = 0;
        // }

        $missing_person->save();

        return response()->json([
            'success' => true,
            'message' => 'The employee is successfully updated',
            'missing_person' => new MissingPersonResource($missing_person)
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
            $missing_person = MissingPerson::findOrFail($id);

            if ($missing_person->user_id === Auth::user()->id || Auth::user()->user_role_id === 1 ||
            Auth::user()->user_role_id === 2 || Auth::user()->user_role_id === 4) {
                Storage::delete('public/missing_persons/'. $missing_person->picture_name);
                $missing_person->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'The missing person is successfully deleted',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The missing person is successfully deleted',
                ]);
            }



        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'You don\'t have the priviledges to edit this data',
            ]);
        }
    }
}
