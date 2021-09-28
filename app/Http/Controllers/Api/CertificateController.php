<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Http\Requests\StoreRequirementRequest;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\RequirementResource;
use App\Models\Certificate;
use App\Models\CertificateRequirement;
use App\Models\Requirement;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::withCount('requirements')->get();
        return CertificateResource::collection($certificates)->additional(['success' => true]);
    }

    public function show(Certificate $certificate)
    {
        return (new CertificateResource($certificate->load('requirements')->loadCount('requirements')))->additional(Helper::instance()->itemFound('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        $statuses = [ (object)[ "id" => 1, "name" => "Available"], (object) ["id" => 0,"name" => "Unavailable"]];
        $deliveryOption = [ (object)[ "id" => 1, "name" => "Open for delivery"], (object) ["id" => 0, "name" => "Walkin Only"]];
        return (new CertificateResource($certificate))->additional(array_merge(['statuses' => $statuses, 'deliveryOption' => $deliveryOption ],Helper::instance()->itemFound('certificate')));
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        $certificate->fill($request->validated())->save();
        return (new CertificateResource($certificate->load('requirements')->loadCount('requirements')))->additional(Helper::instance()->updateSuccess('certificate'));
    }

    public function addRequirement(Certificate $certificate) {

        $id = $certificate->id;

        $requirements = Requirement::whereDoesntHave('certificates', function ($query) use ($id) {
            $query->where('certificates.id', $id);
        })->get();

        return RequirementResource::collection($requirements)->additional(['success' => true]);
    }

    public function storeRequirement(StoreRequirementRequest $request) {
        CertificateRequirement::create($request->validated());
        $certificate = Certificate::with('requirements')->withCount('requirements')->find($request->certificate_id);
        return (new CertificateResource($certificate))->additional(Helper::instance()->itemFound('certificate requirement'));
    }

    public function destroyRequirement(Certificate $certificate, Requirement $requirement) {
        $certificate->requirements()->detach($requirement->id);
        return response()->json(Helper::instance()->destroySuccess('certificate requirement'));
    }

}
