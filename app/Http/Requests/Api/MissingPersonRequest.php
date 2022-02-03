<?php

namespace App\Http\Requests\Api;


use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class MissingPersonRequest extends FormRequest
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
                'height' => 'required|numeric|between:1,1000.99',
                'height_unit' => ['required', Rule::in(['feet(ft)', 'centimeter(cm)'])],
                'weight' => 'required|numeric|between:1,1000.99',
                'weight_unit' => ['required', Rule::in(['kilogram(kg)', 'pound(lbs)'])],
                'age' => 'integer|between:1,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:250',
                'important_information' => 'required|string|min:3|max:250',
                'last_seen' => 'required|string|min:3|max:60',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'picture' =>  'required|base64image',
                'credential' =>  'required|base64image',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'name' => 'required|string|min:3|max:50',
                'height' => 'required|numeric|between:1,200',
                'height_unit' => ['required', Rule::in(['feet(ft)', 'centimeter(cm)'])],
                'weight' => 'required|numeric|between:1,500',
                'weight_unit' => ['required', Rule::in(['kilogram(kg)', 'pound(lbs)'])],
                'age' => 'integer|between:1,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:120',
                'important_information' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:60',
                'email' => 'required|max:30|email',
                'phone_no' => 'required|numeric',
                'picture' =>  'base64image',
                'credential' =>  'base64image',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
            ];
        }
    }

    public function getData() {
        $data = $this->only(['name', 'contact_user_id', 'height', 'height_unit', 'weight', 'weight_unit', 'age', 'eyes', 'hair', 'unique_sign', 'phone_no', 'email',
            'important_information', 'last_seen', 'report_type']);
        return $data;
    }
}
