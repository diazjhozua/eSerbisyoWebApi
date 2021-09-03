<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ComplaintTypeResource;
use App\Models\Complaint;
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
    public function store(Request $request)
    {
        $rules = array(
            'complaint_type_id' => 'integer|exists:complaint_types,id',
            'custom_type' => 'string|min:1|max:60',
            'reason' => 'required:string|min:4|max:500',
            'action' => 'required:string|min:4|max:500',
            'status' => ['integer', new ValidReportStatus],
            'complainant_list' => 'required|array|min:3',
            'complainant_list.complaint_id' => 'required|integer|exists:complaints,id',
            'complainant_list.name' => 'required|string|min:1|max:60',
            'complainant_list.signature' => 'required|mimes:jpeg,png|max:3000',
            'defendant_list' => 'required|array|min:2',
            'defendant_list.complaint_id' => 'required|integer|exists:complaints,id',
            'defendant_list.name' => 'required|string|min:1|max:60',
        );

        try {
            DB::transaction();
            // DB::insert(...);
            // DB::insert(...);
            // DB::insert(...);
            $complaint = new Complaint();
            $complaint->complaint_type_id = $request->complaint_type_id;
            $complaint->custom_type = $request->custom_type;
            $complaint->reason = $request->reason;
            $complaint->action = $request->action;

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
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
