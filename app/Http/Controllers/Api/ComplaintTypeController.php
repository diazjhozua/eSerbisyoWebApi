<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ComplaintTypeResource;
use App\Models\ComplaintType;
use App\Helper\Helper;

class ComplaintTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function noItemFound()
    {
        return response()->json([
            'success' => false,
            'message' => 'No complaint type id found',
        ]);
    }
    public function index()
    {
        $complaint_types = ComplaintType::withCount('complaints')->orderBy('complaints_count', 'DESC')->get();

        return response()->json([
            'success' => true,
            'complaint_types' => ComplaintTypeResource::collection($complaint_types)
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
            'type' => 'required|unique:document_types|string|min:4|max:120',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $complaint_type = new ComplaintType();
        $complaint_type->type = $request->type;
        $complaint_type->save();
        $complaint_type->complaints_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New complaint type created succesfully',
            'complaint_type' => new ComplaintTypeResource($complaint_type)
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
            $complaint_type = ComplaintType::with(['complaints' => function($query){
                $query->orderBy('created_at', 'DESC');
                $query->withCount('complainant_lists');
                $query->withCount('defendant_lists');
            }])->withCount('complaints')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found complaint type data',
                'complaint_type' => new ComplaintTypeResource($complaint_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint type'));
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
            $complaint_type = ComplaintType::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found document type data',
                'complaint_type' => new ComplaintTypeResource($complaint_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint type'));
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
            'type' => 'required|unique:document_types|string|min:4|max:120'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        try {
            $complaint_type = ComplaintType::withCount('complaints')->findOrFail($id);
            $complaint_type->type = $request->type;
            $complaint_type->save();

            return response()->json([
                'success' => true,
                'message' => 'The complaint type is successfully updated',
                'complaint_type' => new ComplaintTypeResource($complaint_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint type'));
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
            $complaint_type = ComplaintType::findOrFail($id);
            $complaint_type->delete();
            return response()->json([
                'success' => true,
                'message' => 'The complaint type is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint type'));
        }
    }
}
