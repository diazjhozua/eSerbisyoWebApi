<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DefendantRequest;
use App\Http\Resources\DefendantResource;
use App\Models\Complaint;
use App\Models\Defendant;
use Illuminate\Http\Request;

class DefendantController extends Controller
{
    public function store(DefendantRequest $request)
    {
        $complaint = Complaint::find($request->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only add defendant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only add defendant to your complaint when the status is Pending or Denied"], 403);
        }

        $defendant = Defendant::create($request->validated());
        return (new DefendantResource($defendant))->additional(Helper::instance()->storeSuccess('defendant'));
    }

    public function edit(Defendant $defendant)
    {
        $complaint = Complaint::find($defendant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only edit defendant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only edit defendant to your complaint when the status is Pending or Denied"], 403);
        }

        return (new DefendantResource($defendant))->additional(array_merge(Helper::instance()->itemFound('defendant')));
    }

    public function update(DefendantRequest $request, Defendant $defendant)
    {
        $complaint = Complaint::find($defendant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update defendant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only update defendant to your complaint when the status is Pending or Denied"], 403);
        }
        $defendant->fill($request->validated())->save();
        return (new DefendantResource($defendant))->additional(Helper::instance()->updateSuccess('defendant'));
    }

    public function destroy(Defendant $defendant)
    {
        $complaint = Complaint::find($defendant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only delete defendant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only delete defendant to your complaint when the status is Pending or Denied"], 403);
        }
        $defendant->delete();
        return response()->json(Helper::instance()->destroySuccess('defendant'));
    }
}
