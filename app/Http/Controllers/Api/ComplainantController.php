<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ComplainantRequest;
use App\Http\Resources\ComplainantResource;
use App\Models\Complainant;
use App\Models\Complaint;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Log;

class ComplainantController extends Controller
{
    public function store(ComplainantRequest $request)
    {
        activity()->disableLogging();
        $complaint = Complaint::find($request->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only add complainant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only add complainant to your complaint when the status is Pending or Denied"], 403);
        }

        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->signature, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

        $complainant = Complainant::create(array_merge($request->getData(), ['signature_picture' => $result->getPublicId(),'file_path' => $result->getPath()]));
        return (new ComplainantResource($complainant->load('complaint')))->additional(Helper::instance()->storeSuccess('complainant'));
    }

    public function edit(Complainant $complainant)
    {
        $complaint = Complaint::find($complainant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only edit complainant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only edit complainant to your complaint when the status is Pending or Denied"], 403);
        }
        return (new ComplainantResource($complainant))->additional(array_merge(Helper::instance()->itemFound('complainant')));
    }

    public function update(ComplainantRequest $request, Complainant $complainant)
    {
        activity()->disableLogging();
        $complaint = Complaint::find($complainant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only update complainant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only update complainant to your complaint when the status is Pending or Denied"], 403);
        }

        if($request->signature != ''){
            Cloudinary::destroy($complainant->signature_picture);

            $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->signature, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);

            $complainant->fill(array_merge($request->getPutData(), ['signature_picture' => $result->getPublicId(),'file_path' => $result->getPath()]))->save();

        } else {
            $complainant->fill($request->getPutData())->save();
        }

        return (new ComplainantResource($complainant))->additional(Helper::instance()->updateSuccess('complainant'));
    }

    public function destroy(Complainant $complainant)
    {
        activity()->disableLogging();
        $complaint = Complaint::find($complainant->complaint_id);
        if ($complaint->contact_user_id != auth('api')->user()->id) {
            return response()->json(["message" => "You can only delete complainant to your submitted complaint."], 403);
        }

        if ($complaint->status == 'Approved' || $complaint->status == 'Resolved') {
            return response()->json(["message" => "You can only delete complainant to your complaint when the status is Pending or Denied"], 403);
        }

        Cloudinary::destroy($complainant->signature_picture);
        $complainant->delete();
        return response()->json(Helper::instance()->destroySuccess('complainant'));
    }
}
