<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ComplaintTypeResource;
use App\Models\Complaint;
use App\Models\ComplainantList;
use App\Models\DefendantList;
use App\Models\ComplaintType;
use App\Rules\ValidReportStatus;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaints = Complaint::with('complaint_type')->withCount('complainant_lists', 'defendant_lists')->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'complaints' => ComplaintResource::collection($complaints)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // load all complaint_type to select the user
        $document_types = ComplaintType::get();

        return response()->json([
            'success' => true,
            'document_types' => ComplaintTypeResource::collection($document_types)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComplaintRequest $request)
    {

        DB::transaction(function($request) {
            $complaint = new Complaint();
            $complaint->complaint_type_id = $request->complaint_type_id;
            $complaint->custom_type = $request->custom_type;
            $complaint->reason = $request->reason;
            $complaint->action = $request->action;
            $complaint->status = 2;
            $complaint->save();

            $complainant_lists_count = 0;
            foreach ($request->complainant_list as $complainant) {
                $complainant_lists_count ++;
                $complainant = new ComplainantList();
                $complainant->complaint_id = $complaint->id;
                $complainant->name = $complainant->name;

                $fileName = time().'_'.$complainant->signature->getClientOriginalName();
                $filePath = $complainant->file('signature')->storeAs('signatures', $fileName, 'private');

                $complainant->signature_picture = $fileName;
                $complainant->file_path = $filePath;

                $complainant->save();
            }

            $defendant_lists_count = 0;
            foreach ($request->defendant_list as $defendant) {
                $defendant_lists_count ++;
                $defendant = new DefendantList();
                $defendant->complaint_id = $complaint->id;
                $defendant->name = $complainant->name;

                $defendant->save();
            }

            $complaint->complainant_lists_count = $complainant_lists_count;
            $complaint->defendant_lists_count = $defendant_lists_count;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'New complaint created succesfully',
                'complaint' => new ComplaintResource($complaint->load('complaint_type', 'complainant_lists', 'defendant_lists'))
            ]);
      });

      return response()->json([
        'success' => false,
        'message' => 'Failed',
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
            $complaint = Complaint::with('complaint_type', 'complainant_lists', 'defendant_lists')->withCount('complainant_lists', 'defendant_lists')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found complaint data',
                'complaint' => new ComplaintResource($complaint)
            ]);
        } catch (ModelNotFoundException $ex) {
            return response()->json(Helper::instance()->noItemFound('complaint'));
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
