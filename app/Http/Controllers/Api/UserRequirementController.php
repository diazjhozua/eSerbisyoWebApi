<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserRequirement;
use Illuminate\Http\Request;

class UserRequirementController extends Controller
{
    public function index()
    {
        $userRequirements = UserRequirement::where('user_id',auth('api')->user()->id)->orderBy('created_at', 'DESC')->get();

        return response()->json(["data" => $userRequirements], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(UserRequirement $userRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserRequirement  $userRequirement
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRequirement $userRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRequirement  $userRequirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRequirement $userRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRequirement  $userRequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRequirement $userRequirement)
    {
        //
    }
}
