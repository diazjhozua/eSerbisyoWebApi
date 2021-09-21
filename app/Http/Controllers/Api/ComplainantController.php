<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplainantRequest;
use App\Http\Resources\ComplainantResource;
use App\Models\Complainant;
use Illuminate\Support\Facades\Storage;

class ComplainantController extends Controller
{
    public function store(ComplainantRequest $request)
    {
        $fileName = time().'_'.$request->signature->getClientOriginalName();
        $filePath = $request->file('signature')->storeAs('signatures', $fileName, 'public');
        $complainant = Complainant::create(array_merge($request->getData(), ['signature_picture' => $fileName,'file_path' => $filePath]));
        return (new ComplainantResource($complainant->load('complaint')))->additional(Helper::instance()->storeSuccess('complainant'));
    }

    public function edit(Complainant $complainant)
    {
        return (new ComplainantResource($complainant))->additional(array_merge(Helper::instance()->itemFound('complainant')));
    }

    public function update(ComplainantRequest $request, Complainant $complainant)
    {
        if($request->hasFile('signature')) {
            Storage::delete('public/signatures/'. $complainant->signature_picture);
            $fileName = time().'_'.$request->signature->getClientOriginalName();
            $filePath = $request->file('signature')->storeAs('signatures', $fileName, 'public');
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
