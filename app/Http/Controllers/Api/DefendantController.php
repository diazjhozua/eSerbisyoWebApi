<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DefendantRequest;
use App\Http\Resources\DefendantResource;
use App\Models\Defendant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DefendantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(DefendantRequest $request)
    {
        $defendant = new Defendant();
        $defendant->complaint_id = $request->complaint_id;
        $defendant->name = $request->name;

        $defendant->save();

        return response()->json([
            'success' => true,
            'message' => 'New defendant created succesfully in the complaint_id: '.$defendant->complaint_id,
            'defendant' => new DefendantResource($defendant)
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
        try {
            $defendant = Defendant::findOrFail($id);

            return response()->json([
                'success' => true,
                'defendant' => new DefendantResource($defendant)
            ]);
        } catch (ModelNotFoundException $ex) {
            return response()->json(Helper::instance()->noItemFound('defendant'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DefendantRequest $request)
    {
        try {
            $defendant = Defendant::where('complaint_id',$request->complaint_id)->findOrFail($request->id);
            $defendant->name = $request->name;
            $defendant->save();

            return response()->json([
                'success' => true,
                'message' => 'New defendant updated succesfully in the complaint_id: '.$defendant->complaint_id,
                'defendant' => new DefendantResource($defendant)
            ]);

        } catch (ModelNotFoundException $ex) {
            return response()->json( [
                'success' => false,
                'message' => "Defendant not found on a specific complaint id report",
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
            $defendant = Defendant::findOrFail($id);
            $complaint_id = $defendant->complaint_id;
            $defendant->delete();
            return response()->json([
                'success' => true,
                'message' => 'The defendant is successfully deleted in the complaint_id: '.$complaint_id,
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('defendant'));
        }
    }
}
