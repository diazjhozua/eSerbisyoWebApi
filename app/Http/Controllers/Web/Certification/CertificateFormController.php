<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateFormRequest;
use App\Http\Resources\CertificateFormResource;
use App\Models\CertificateForm;
use Helper;
use Illuminate\Http\Request;

class CertificateFormController extends Controller
{
    public function edit(CertificateForm $certificateForm)
    {
        return (new CertificateFormResource($certificateForm->load('certificate')))->additional(array_merge(Helper::instance()->itemFound('certificate form')));
    }

    public function update(CertificateFormRequest $request, CertificateForm $certificateForm)
    {
        switch ($request->certificate_id) {
            case 1: //brgyIndigency
                $certificateForm->fill($request->getPutIndigencyData())->save();
                break;
            case 2: //brgyCedula
                $certificateForm->fill($request->getPutCedulaData())->save();
                break;
            case 3: //brgyClearance
                $certificateForm->fill($request->getPutClearanceData())->save();
                break;
            case 4: //brgyID
                $certificateForm->fill($request->getPutIDData())->save();
                break;
            case 5:
                // business permit
                $certificateForm->fill($request->getPutBusinessData())->save();
                break;
            default:
        }

        return response()->json(['message' => 'Certificate form is updated' ], 200);
    }
}
