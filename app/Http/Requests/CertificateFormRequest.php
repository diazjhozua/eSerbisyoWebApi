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

        if ($this->isMethod('POST')) {
            $vallidation = [
                'certificate_id' => ['required', Rule::exists('certificates', 'id')->where(function ($query) {
                    return $query->where('status', 'Available');
                })],
                'name' => ['required', 'string', 'min:4', 'max:150'],
                'address' => ['required', 'string', 'min:4', 'max:250'],
                'signature' => 'required|mimes:jpeg,png|max:3000',
            ];
        }

        if ($this->isMethod('PUT')) {
            $vallidation = [
                'certificate_id' => ['required', Rule::exists('certificates', 'id')->where(function ($query) {
                    return $query->where('status', 'Available');
                })],
                'name' => ['required', 'string', 'min:4', 'max:150'],
                'address' => ['required', 'string', 'min:4', 'max:250'],
                'signature' => 'mimes:jpeg,png|max:3000',
            ];
        }

        switch ($certificate_id) {
            case 1: //brgyIndigency
                $vallidation = array_merge($vallidation, [
                    'purpose' => ['required', 'string', 'min:5', 'max:250']
                ]);
                break;
            case 2: //brgyCedula
                $vallidation = array_merge($vallidation, [
                    'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
                    'birthplace' => ['required', 'string', 'min:5', 'max:250'],
                    'citizenship' => ['required', 'string', 'min:5', 'max:250'],
                    'civil_status' => ['required', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
                ]);
                break;
            case 3: //brgyClearance
                $vallidation = array_merge($vallidation, [
                    'purpose' => ['required', 'string', 'min:5', 'max:250']
                ]);
                break;
            case 4: //brgyID
                $vallidation = array_merge($vallidation, [
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

    public function getPostIndigencyData() {
        return $this->only(['certificate_id', 'name', 'address', 'purpose']);
    }

    public function getPutIndigencyData() {
        return $this->only(['name', 'address', 'purpose']);
    }

    public function getPostCedulaData() {
        return $this->only(['certificate_id', 'name', 'address', 'birthday', 'birthplace', 'citizenship', 'civil_status']);
    }

    public function getPutCedulaData() {
        return $this->only(['name', 'address', 'birthday', 'birthplace', 'citizenship', 'civil_status']);
    }

    public function getPostClearanceData() {
        return $this->only(['certificate_id', 'name', 'address', 'purpose']);
    }

    public function getPutClearanceData() {
        return $this->only(['name', 'address', 'purpose']);
    }

    public function getPostIDData() {
        return $this->only(['certificate_id', 'name', 'address', 'contact_no', 'contact_person', 'contact_person_no', 'contact_person_relation']);
    }

    public function getPutIDData() {
        return $this->only(['name', 'address', 'contact_no', 'contact_person', 'contact_person_no', 'contact_person_relation']);
    }

    public function getPostBusinessData() {
        return $this->only(['certificate_id', 'name', 'address', 'purpose', 'business_name']);
    }

    public function getPutBusinessData() {
        return $this->only(['name', 'address', 'purpose', 'business_name']);
    }
}
