<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;
use Log;

class OrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|max:150|email:rfc,dns',
            'phone_no' => 'required|numeric',
            'location_address' => 'required|string|min:3|max:200',
            'pick_up_type' => ['required', Rule::in(['Pickup', 'Delivery'])],
            'certificate_forms' => 'required|array|between:1,5',
            'certificate_forms.*.first_name' => 'required|string|min:4|max:150',
            'certificate_forms.*.middle_name' => 'required|string|min:4|max:150',
            'certificate_forms.*.last_name' => 'required|string|min:4|max:150',
            'certificate_forms.*.address' => 'required|string|min:4|max:100',
            'certificate_forms.*.birthday' => ['date', 'date_format:Y-m-d'],
            'certificate_forms.*.civil_status' => [Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            'certificate_forms.*.citizenship' => 'string|min:4|max:50',
            'certificate_forms.*.birthplace' => 'string|min:4|max:150',
            'certificate_forms.*.purpose' => 'string|min:4|max:150',
            'certificate_forms.*.business_name' => 'string|min:4|max:150',
            'certificate_forms.*.profession' => 'string|min:4|max:50',
            'certificate_forms.*.height' => 'numeric|between:1,10.99',
            'certificate_forms.*.weight' => 'numeric|between:1,200.99',
            'certificate_forms.*.sex' => [Rule::in(['Male', 'Female'])],
            'certificate_forms.*.cedula_type' => [Rule::in(['Individual', 'Corporation'])],
            'certificate_forms.*.tin_no' => 'integer|between:111111111,999999999',
            'certificate_forms.*.icr_no' => 'integer|between:111111111,999999999',
            'certificate_forms.*.basic_tax' => 'numeric|between:1,5',
            'certificate_forms.*.additional_tax' => 'numeric|between:1,5000',
            'certificate_forms.*.gross_receipt_preceding' => 'numeric|between:1,5000',
            'certificate_forms.*.gross_receipt_profession' => 'numeric|between:1,5000',
            'certificate_forms.*.real_property' => 'numeric|between:1,5000',
            'certificate_forms.*.interest' => 'numeric|between:0,100',
            'certificate_forms.*.contact_no' => 'numeric',
            'certificate_forms.*.contact_person' => 'string|min:3|max:100',
            'certificate_forms.*.contact_person_no' => 'numeric',
            'certificate_forms.*.contact_person_relation' => 'string|min:3|max:100',
        ];
    }
}
