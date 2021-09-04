<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\PositionResource;
use App\Models\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::withCount('employees')->get();

        return response()->json([
            'success' => true,
            'positions' => PositionResource::collection($positions)
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
    public function store(PositionRequest $request)
    {
        $position = new Position();
        $position->id = $request->id;
        $position->position = $request->position;
        $position->save();
        $position->employees_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New position created succesfully',
            'position' => new PositionResource($position)
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
        //show all employees on specific position id

        try {
            $position = Position::with('employees.term')->withCount('employees')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found position data',
                'position' => new PositionResource($position)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('position'));
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
            $position = Position::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found position data',
                'position' => new PositionResource($position)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('position'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, $id)
    {
        try {
            $position = Position::withCount('employees')->findOrFail($request->id);
            $position->id = $request->id;
            $position->position = $request->position;
            $position->save();

            return response()->json([
                'success' => true,
                'message' => 'The position is successfully updated',
                'position' => new PositionResource($position)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('position'));
        }
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
            $position = Position::findOrFail($id);
            $position->delete();

            return response()->json([
                'success' => true,
                'message' => 'The position is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('position'));
        }
    }
}
