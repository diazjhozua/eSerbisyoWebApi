<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainantRequest;
use App\Http\Resources\ComplainantResource;
use App\Models\Complainant;
use App\Models\Complaint;
use Helper;
use Illuminate\Http\Request;
use Storage;

class ComplainantController extends Controller
{
    public function store(ComplainantRequest $request)
    {
        $image_parts = explode(";base64,", $request->signature);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid().time().'.'.$image_type;
        $filePath = 'signatures/'.$fileName;
        // save to storage/app/photos as the new $filename
        Storage::disk('public')->put($filePath, $image_base64);

        $complainant = Complainant::create(array_merge($request->getData(), ['signature_picture' => $fileName,'file_path' => $filePath]));
        return (new ComplainantResource($complainant->load('complaint')))->additional(Helper::instance()->storeSuccess('complainant'));
    }

    public function edit(Complainant $complainant)
    {
        return (new ComplainantResource($complainant))->additional(array_merge(Helper::instance()->itemFound('complainant')));
    }

    public function update(ComplainantRequest $request, Complainant $complainant)
    {
        if($request->signature != null) {
            $image_parts = explode(";base64,", $request->signature);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid().time().'.'.$image_type;
            $filePath = 'signatures/'.$fileName;
            // save to storage/app/photos as the new $filename
            Storage::disk('public')->put($filePath, $image_base64);
            $complainant->fill(array_merge($request->getData(), ['signature_picture' => $fileName,'file_path' => $filePath]))->save();
        } else { $complainant->fill($request->getData())->save(); }

        return (new ComplainantResource($complainant))->additional(Helper::instance()->updateSuccess('complainant'));
    }

    public function destroy(Complainant $complainant)
    {
        Storage::delete('public/signatures/'. $complainant->signature_picture);
        $complainant->delete();
        return response()->json(Helper::instance()->destroySuccess('complainant'));
    }
}
