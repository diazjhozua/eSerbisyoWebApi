<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;
use Log;

class OrderAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'location_address' => 'required|string|min:3|max:200',
                'certificate_forms' => 'required|array|between:1,10',
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
        if ($this->isMethod('PUT')) {
            return [
                'order_status' => [Rule::in(['Received', 'DNR', 'On-Going'])],
                'is_returned' => [Rule::in(['Yes', 'No'])],
                'delivery_payment_status' => [Rule::in(['Pending', 'Received'])],
            ];
        }
    }

        protected function prepareForValidation(): void
    {
        if ($this->isMethod('POST')) {

            $certificate_forms = $this->get('certificate_forms');

            $decoded_certificate_forms = [];
            foreach ($certificate_forms as $certificateForm)  {
                array_push($decoded_certificate_forms,  (array) json_decode($certificateForm, false));
            }

            $this->merge([
                'certificate_forms' => $decoded_certificate_forms,
            ]);

            Log::debug($this->request->all());

            Log::debug($this->get('certificate_forms'));
        }
    }

    public function getData() {
        $data = $this->only(['name', 'email', 'phone_no',  'location_address']);
        return $data;
    }


}
