<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        $rules = array(
            'id' => 'required|integer|unique:positions',
            'position' => 'required|string||unique:positions|min:5|max:60',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

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
            return response()->json([
                'success' => false,
                'message' => 'No position id found',
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
            $position = Position::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found position data',
                'position' => new PositionResource($position)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No position id found',
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
        $rules = array(
            'id' => 'required|integer|exists:positions,id',
            'position' => 'required|string||unique:positions|min:5|max:60',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

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
            return response()->json([
                'success' => false,
                'message' => 'No position id found',
            ]);
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
            return response()->json([
                'success' => false,
                'message' => 'No position id found',
            ]);
        }
    }
}
