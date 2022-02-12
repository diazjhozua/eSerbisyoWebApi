<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateFormRequest;
use App\Http\Resources\CertificateFormResource;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Models\CertificateForm;

class CertificateFormController extends Controller
{

    public function index()
    {
        $certificateForms = CertificateForm::with('certificate')->orderBy('created_at','DESC')->get();
        return CertificateFormResource::collection($certificateForms)->additional(['success' => true]);
    }

    public function create()
    {
        $certificates = Certificate::where('status','Available')->get();
        $civilStatuses = [ (object)[ "id" => 1, "type" => "Single"],(object) ["id" => 2,"type" => "Married"], (object) ["id" => 3,"type" => "Divorced"], (object) ["id" => 4,"type" => "Widowed"] ];
        return ['certificates' => CertificateResource::collection($certificates), '$civilStatuses' => $civilStatuses, 'success' => true];
    }

    public function store(CertificateFormRequest $request)
    {
        $fileName = time().'_'.$request->signature->getClientOriginalName();
        $filePath = $request->file('signature')->storeAs('signatures', $fileName, 'public');

        $image = ['status' => 'Pending', 'user_id' => 2, 'signature_picture' => $fileName, 'file_path' => $filePath];
        switch ($request->certificate_id) {
            case 1: //brgyIndigency
                $certificateForm = CertificateForm::create(array_merge($request->getPostIndigencyData(), $image));
                break;
            case 2: //brgyCedula
                $certificateForm = CertificateForm::create(array_merge($request->getPostCedulaData(), $image));
                break;
            case 3: //brgyClearance
                $certificateForm = CertificateForm::create(array_merge($request->getPostCedulaData(), $image));
                break;
            case 4: //brgyID
                $certificateForm = CertificateForm::create(array_merge($request->getPostIDData(), $image));
                break;
            case 5: //businessPermit
                $certificateForm = CertificateForm::create(array_merge($request->getPostBusinessData(), $image));
                break;
        }

        return (new CertificateFormResource($certificateForm->load('certificate')))->additional(Helper::instance()->storeSuccess('certificate-form'));
    }

    public function show(CertificateForm $certificate_form_request)
    {
        return (new CertificateFormResource($certificate_form_request->load('certificate', 'requirements')->loadCount('requirements')))->additional(Helper::instance()->itemFound('certificate-form'));

    }

    public function edit(CertificateForm $certificate_form_request)
    {
        //
    }

    public function update(CertificateFormRequest $request, CertificateForm $certificate_form_request)
    {
        //
    }

    public function destroy(CertificateForm $certificate_form_request)
    {
        //
    }
}
