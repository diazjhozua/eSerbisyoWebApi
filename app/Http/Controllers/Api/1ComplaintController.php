<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\ComplaintRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ComplaintTypeResource;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Defendant;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function changeStatus(ChangeStatusRequest $request, $id) {
        try {
            $complaint = Complaint::with('complaint_type', 'complainants', 'defendants')->withCount('complainants', 'defendants')->findOrFail($id);

            if ($request->status == $complaint->status) {
                return response()->json(Helper::instance()->sameStatusMessage($request->status, 'Complaint'));
            }

            $oldStatus = $complaint->status;

            $complaint->status = $request->status;
            $complaint->save();

            return response()->json([
                'success' => true,
                'message' => Helper::instance()->statusMessage($oldStatus, $request->status, 'Complaint'),
                'complaint' => new ComplaintResource($complaint)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint'));
        }
    }

    public function index()
    {
        $complaints = Complaint::with('complaint_type')->withCount('complainants', 'defendants')->orderBy('created_at', 'DESC')->get();

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
        $complaint_types = ComplaintType::get();

        return response()->json([
            'success' => true,
            'complaint_types' => ComplaintTypeResource::collection($complaint_types)
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
        $result = DB::transaction(function() use ($request) {

            $complaint = new Complaint();
            $complaint->complaint_type_id = $request->complaint_type_id;
            $complaint->custom_type = $request->custom_type;
            $complaint->reason = $request->reason;
            $complaint->action = $request->action;
            $complaint->status = 2;
            $complaint->save();

            $complainant_lists_count = 0;

            foreach ($request->complainant_list as $key => $value) {

                $complainant_lists_count ++;
                $complainant = new Complainant();
                $complainant->complaint_id = $complaint->id;
                $complainant->name = $value['name'];

                $fileName = time().'_'.$value['signature']->getClientOriginalName();

                $filePath =   $value['signature']->storeAs('signatures', $fileName, 'public');

                $complainant->signature_picture = $fileName;
                $complainant->file_path = $filePath;

                $complainant->save();
            }

            $defendant_lists_count = 0;

            foreach ($request->defendant_list as $key => $value) {
                $defendant_lists_count ++;
                $defendant = new Defendant();
                $defendant->complaint_id = $complaint->id;
                $defendant->name = $value['name'];

                $defendant->save();
            }

            $complaint->complainant_lists_count = $complainant_lists_count;
            $complaint->defendant_lists_count = $defendant_lists_count;

            DB::commit();

            return [
                'success' => true,
                'message' => 'New complaint created succesfully',
                'complaint' => new ComplaintResource($complaint->load('complaint_type', 'complainants', 'defendants'))
            ];
        });

        return response()->json($result);
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
            $complaint = Complaint::with('complaint_type', 'complainants', 'defendants')->withCount('complainants', 'defendants')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found complaint data',
                'complaint' => new ComplaintResource($complaint)
                // 'complaint' => $complaint
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
        try {
            $complaint = Complaint::with('complaint_type', 'complainants', 'defendants')->withCount('complainants', 'defendants')->findOrFail($id);
            $complaint_types = ComplaintType::get();
            $complaint_statuses = [
                (object)["id" => 1, "status" => "For Approval"],
                (object)["id" => 2, "status" => "Approved"],
                (object)["id" => 3, "status" => "Denied"],
                (object)["id" => 4, "status" => "Resolved"],
            ];
            return response()->json([
                'success' => true,
                'message' => 'Found complaint data',
                'complaint' => new ComplaintResource($complaint),
                'complaint_types' => $complaint_types,
                'complaint_statuses' => $complaint_statuses
            ]);
        } catch (ModelNotFoundException $ex) {
            return response()->json(Helper::instance()->noItemFound('complaint'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComplaintRequest $request, $id)
    {
        try {
            $complaint = Complaint::with('complaint_type', 'complainants', 'defendants')->withCount('complainants', 'defendants')->findOrFail($id);
            $complaint->complaint_type_id = $request->complaint_type_id;
            $complaint->custom_type = $request->custom_type;
            $complaint->reason = $request->reason;
            $complaint->action = $request->action;
            $complaint->status = 2;

            // if (Auth::user()->user_role_id == 1 || Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4) {
            //     $lost_and_found->status = $request->status;
            // } else {
            //     // if the user is resident, the missing report if approved already would become for approval
            //     $lost_and_found->status = 1;
            // }

            $complaint->save();

            return response()->json([
                'success' => true,
                'message' => 'The complaint is successfully updated',
                'complaint' => new ComplaintResource($complaint)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('complaint'));
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
            $result = DB::transaction(function() use ($id) {
                $complaint =  Complaint::with('complaint_type', 'complainants', 'defendants')->findOrFail($id);

                foreach ($complaint->complainants as $complainant) {
                    Storage::delete('public/signatures/'. $complainant->signature_picture);
                }

                Complainant::where('complaint_id', $complaint->id)->delete();
                Defendant::where('complaint_id', $complaint->id)->delete();

                $complaint->delete();

                return [
                    'success' => true,
                    'message' => 'The complaint is successfully deleted',
                ];

            });

            return response()->json($result);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('document'));
        }
    }
}
