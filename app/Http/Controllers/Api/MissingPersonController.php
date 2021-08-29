<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $rules = array(
            'name' => 'required|string|min:3|max:50',
            'height' => 'required|numeric|regex:/^(?:d*.d{1,2}|d+)$/',
            'weight' => 'required|numeric|regex:/^(?:d*.d{1,2}|d+)$/',
            'age' => 'required|integer|between:0,200',
            'eyes' => 'required|string|min:3|max:50',
            'hair' => 'required|string|min:3|max:50',
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
        //
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
