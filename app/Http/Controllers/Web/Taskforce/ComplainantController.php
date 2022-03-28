<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainantRequest;
use App\Http\Resources\ComplainantResource;
use App\Models\Complainant;
use App\Models\Complaint;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Helper;
use Illuminate\Http\Request;
use Storage;

class ComplainantController extends Controller
{
    public function store(ComplainantRequest $request)
    {
        $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->signature, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
        $complainant = Complainant::create(array_merge($request->getData(), ['signature_picture' => $result->getPublicId(),'file_path' =>$result->getPath()]));
        return (new ComplainantResource($complainant->load('complaint')))->additional(Helper::instance()->storeSuccess('complainant'));
    }

    public function edit(Complainant $complainant)
    {
        return (new ComplainantResource($complainant))->additional(array_merge(Helper::instance()->itemFound('complainant')));
    }

    public function update(ComplainantRequest $request, Complainant $complainant)
    {
        if($request->signature != null) {
            Cloudinary::destroy($complainant->signature_picture);
            $result = cloudinary()->uploadFile('data:image/jpeg;base64,'.$request->signature, ['folder' => env('CLOUDINARY_PATH', 'dev-barangay')]);
            $complainant->fill(array_merge($request->getData(), ['signature_picture' => $result->getPublicId(),'file_path' =>$result->getPath()]))->save();
        } else { $complainant->fill($request->getData())->save(); }

        return (new ComplainantResource($complainant))->additional(Helper::instance()->updateSuccess('complainant'));
    }

    public function destroy(Complainant $complainant)
    {
        Cloudinary::destroy($complainant->signature_picture);
        $complainant->delete();
        return response()->json(Helper::instance()->destroySuccess('complainant'));
    }
}
