<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class CertificateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $certificate_id = $this->get('certificate_id');
            //  'certificate_id' => ['required', Rule::exists('certificates', 'id')->where(function ($query) {
            //         return $query->where('status', 'Available');
            //     })],
        if ($this->isMethod('PUT')) {
            $vallidation = [
                'certificate_id' => ['required', Rule::exists('certificates', 'id')],
                'first_name' => ['required', 'string', 'min:4', 'max:150'],
                'middle_name' => ['required', 'string', 'min:4', 'max:150'],
                'last_name' => ['required', 'string', 'min:4', 'max:150'],
                'address' => ['required', 'string', 'min:4', 'max:250'],
            ];
        }

        switch ($certificate_id) {
            case 1: //brgyIndigency
                $vallidation = array_merge($vallidation, [
                    'civil_status' => ['required' ,Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
                    'birthday' => ['required', 'date', 'date_format:Y-m-d'],
                    'citizenship' => 'string|min:4|max:50',
                    'purpose' => ['required', 'string', 'min:5', 'max:250']
                ]);
                break;
            case 2: //brgyCedula
                $vallidation = array_merge($vallidation, [
                    'civil_status' => ['required' ,Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
                    'birthday' => ['required', 'date', 'date_format:Y-m-d'],
                    'citizenship' => 'string|min:4|max:50',
                    'profession' => 'string|min:4|max:50',
                    'height' => 'numeric|between:1,10.99',
                    'weight' => 'numeric|between:1,200.99',
                    'sex' => [Rule::in(['Male', 'Female'])],
                    'cedula_type' => [Rule::in(['Individual', 'Corporation'])],
                    'tin_no' => 'integer|between:111111111,999999999',
                    'icr_no' => 'integer|between:111111111,999999999',
                    'basic_tax' => 'numeric|between:1,5',
                    'additional_tax' => 'numeric|between:0,5000',
                    'gross_receipt_preceding' => 'numeric|between:0,5000',
                    'gross_receipt_profession' => 'numeric|between:0,5000',
                    'real_property' => 'numeric|between:0,5000',
                    'interest' => 'numeric|between:0,100',
                ]);
                break;
            case 3: //brgyClearance
                $vallidation = array_merge($vallidation, [
                    'civil_status' => ['required' ,Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
                    'birthday' => ['required', 'date', 'date_format:Y-m-d'],
                    'citizenship' => 'string|min:4|max:50',
                    'purpose' => ['required', 'string', 'min:5', 'max:250']
                ]);
                break;
            case 4: //brgyID
                $vallidation = array_merge($vallidation, [
                    'civil_status' => ['required' ,Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
                    'birthday' => ['required', 'date', 'date_format:Y-m-d'],
                    'contact_no' => ['required', 'phone:PH'],
                    'contact_person' => ['required', 'string', 'min:5', 'max:250'],
                    'contact_person_no' => ['required', 'different:contact_no', 'phone:PH'],
                    'contact_person_relation' => ['required', 'string', 'min:5', 'max:250'],
                ]);
                break;
            case 5:
                $vallidation = array_merge($vallidation, [
                    'business_name' => ['required', 'string', 'min:5', 'max:250'],
                ]);
                break;
            default:
        }

        return $vallidation;
    }


    public function getPutIndigencyData() {
        return $this->only(['first_name', 'middle_name', 'last_name', 'address', 'civil_status', 'birthday', 'citizenship', 'purpose']);
    }

    public function getPutCedulaData() {
        return $this->only([
            'first_name', 'middle_name', 'last_name', 'address', 'civil_status', 'birthday', 'citizenship',
            'profession', 'height', 'weight', 'sex', 'cedula_type', 'tin_no' , 'icr_no', 'basic_tax', 'additional_tax',
            'gross_receipt_preceding', 'gross_receipt_profession' , 'real_property', 'interest',
        ]);
    }

    public function getPutClearanceData() {
        return $this->only(['first_name', 'middle_name', 'last_name', 'address', 'civil_status', 'birthday', 'citizenship', 'purpose']);
    }

    public function getPutIDData() {
        return $this->only([
            'first_name', 'middle_name', 'last_name', 'address', 'civil_status', 'birthday',
            'contact_no', 'contact_person', 'contact_person', 'contact_person_relation']);
    }

    public function getPutBusinessData() {
        return $this->only(['first_name', 'middle_name', 'last_name', 'address', 'business_name']);
    }
}
