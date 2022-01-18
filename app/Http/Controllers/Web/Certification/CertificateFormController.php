<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateFormRequest;
use App\Http\Resources\CertificateFormResource;
use App\Models\Certificate;
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

                $certificate = Certificate::findOrFail($certificateForm->certificate_id);

                $totalTax = $request->basic_tax + $request->additional_tax + ($request->gross_receipt_preceding == null ? 0 : $request->gross_receipt_preceding) +
                ($request->gross_receipt_profession == null ? 0 : $request->gross_receipt_profession) + ($request->real_property == null ? 0 : $request->real_property);

                if ($request->interest == null ? 0 : $request->interest != 0) {
                    $interest = $totalTax * $request->interest;
                    $totalAmountTax = $totalTax + $interest;
                } else {
                    $totalAmountTax = $totalTax;
                }

                $cedulaPrice = $certificate->price + $totalAmountTax;
                $certificateForm->fill(array_merge($request->getPutCedulaData(), ['price_filled' => $cedulaPrice]))->save();
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
