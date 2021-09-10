<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainantRequest;
use App\Http\Resources\ComplainantResource;
use App\Models\Complainant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplainantController extends Controller
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
    public function store(ComplainantRequest $request)
    {
        $complainant = new Complainant();
        $complainant->complaint_id = $request->complaint_id;
        $complainant->name = $request->name;

        $fileName = time().'_'.$request->signature->getClientOriginalName();
        $filePath = $request->file('signature')->storeAs('signatures', $fileName, 'public');

        $complainant->signature_picture = $fileName;
        $complainant->file_path = $filePath;

        $complainant->save();

        return response()->json([
            'success' => true,
            'message' => 'New complainant created succesfully in the complaint_id: '.$complainant->complaint_id,
            'complainant' => new ComplainantResource($complainant)
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
            $complainant = Complainant::findOrFail($id);

            return response()->json([
                'success' => true,
                'complainant' => $complainant
            ]);
        } catch (ModelNotFoundException $ex) {
            return response()->json(Helper::instance()->noItemFound('complainant'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComplainantRequest $request)
    {
        try {
            $complainant = Complainant::where('complaint_id',$request->complaint_id)->findOrFail($request->id);

            $complainant->name = $request->name;

            //check if they want to update the signature file
            if($request->hasFile('signature')) {
                Storage::delete('public/signatures/'. $complainant->signature_picture);

                $fileName = time().'_'.$request->signature->getClientOriginalName();
                $filePath = $request->file('signature')->storeAs('signatures', $fileName, 'public');

                $complainant->signature_picture = $fileName;
                $complainant->file_path = $filePath;
            }

            $complainant->save();

            return response()->json([
                'success' => true,
                'message' => 'New complainant updated succesfully in the complaint_id: '.$complainant->complaint_id,
                'complainant' => new ComplainantResource($complainant)
            ]);

        } catch (ModelNotFoundException $ex) {
            return response()->json( [
                'success' => false,
                'message' => "Complainant not found on a specific complaint id report",
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
            $complainant = Complainant::findOrFail($id);
            $complaint_id = $complainant->complaint_id;
            Storage::delete('public/signatures/'. $complainant->signature_picture);

            $complainant->delete();
            return response()->json([
                'success' => true,
                'message' => 'The complainant is successfully deleted in the complaint_id: '.$complaint_id,
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complainant'));
        }
    }
}
