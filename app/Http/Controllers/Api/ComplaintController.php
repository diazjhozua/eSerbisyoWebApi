<?php

namespace App\Http\Controllers\Api;

use App\Events\ComplaintEvent;
use App\Events\FeedbackEvent;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiComplaintRequest;
use App\Http\Requests\Api\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\TypeResource;
use App\Models\Complainant;
use App\Models\Complaint;
use App\Models\Defendant;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{

    public function index() {
        $complaints = Complaint::with('type')->where('contact_user_id', auth('api')->user()->id)->orderBy('created_at','desc')->get();
        return ComplaintResource::collection($complaints);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return ['types' => TypeResource::collection($types), 'success' => true] ;
    }

    public function store(ApiComplaintRequest $request)
    {
        activity()->disableLogging();
        return DB::transaction(function() use ($request) {
            $complaint = Complaint::create(array_merge($request->getData(), ['status' => 'Pending', 'user_id' => auth('api')->user()->id, 'contact_user_id' => auth('api')->user()->id]));
            $complainantCount = 0;
            $defendantCount = 0;

            foreach ($request->complainant_list as $key => $value) {
                $fileName = uniqid().time().'.jpg';
                $filePath = 'signatures/'.$fileName;
                Storage::disk('public')->put($filePath, base64_decode($value['signature']));

                Complainant::create(['complaint_id' => $complaint->id, 'name' => $value['name'], 'signature_picture' => $fileName,'file_path' => $filePath]);
                $complainantCount++;
            }

            foreach ($request->defendant_list as $key => $value) {
                Defendant::create(['complaint_id' => $complaint->id, 'name' => $value['name']]);
                $defendantCount++;
            }

            $complaint->complainants_count = $complainantCount;
            $complaint->defendants_count = $defendantCount;

            event(new ComplaintEvent($complaint->load('type')));
            return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->storeSuccess('complaint'));
        });
    }
    public function show(Complaint $complaint)
    {
        activity()->disableLogging();
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only view your submitted complaint."], 403);
        }

        return (new ComplaintResource($complaint->load('type', 'complainants', 'defendants')->loadCount('complainants', 'defendants')))->additional(Helper::instance()->itemFound('complaint'));
    }

    public function edit(Complaint $complaint)
    {
        $types = Type::where('model_type', 'Complaint')->get();
        return (new ComplaintResource($complaint->load('type', 'complainants', 'defendants')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('complaint')));
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {
        activity()->disableLogging();
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only update your complaint when the status is Pending or Denied"], 403);
        }

        $complaint->fill(array_merge($request->getData(), ['status' => 'Pending']))->save();
        return (new ComplaintResource($complaint->load('type')))->additional(Helper::instance()->updateSuccess('complaint'));
    }

    public function destroy(Complaint $complaint)
    {
        activity()->disableLogging();
        return DB::transaction(function() use ($complaint) {

            if ($complaint->contact_user_id != auth('api')->user()->id) {
                return response()->json(["message" => "You can only delete your submitted complaint."], 403);
            }

            if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
                return response()->json(["message" => "You can only delete your complaint when the status is Pending or Denied"], 403);
            }

            $complaint->load('complainants');
            foreach ($complaint->complainants as $complainant) { Storage::delete('public/signatures/'. $complainant->signature_picture); }
            Complainant::where('complaint_id', $complaint->id)->delete();
            Defendant::where('complaint_id', $complaint->id)->delete();
            $complaint->delete();

            return response()->json(Helper::instance()->destroySuccess('complaint'));
        });
    }

    // public function changeStatus(ChangeStatusRequest $request, Complaint $complaint) {
    //     if ($request->status == $complaint->status) { return response()->json(Helper::instance()->sameStatusMessage($request->status, 'complaint')); }
    //     $oldStatus = $complaint->status;
    //     $complaint->fill($request->validated())->save();
    //     return (new ComplaintResource($complaint))->additional(Helper::instance()->statusMessage($oldStatus, $complaint->status, 'complaint'));
    // }
}
