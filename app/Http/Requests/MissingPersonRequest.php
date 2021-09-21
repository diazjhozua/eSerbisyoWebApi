<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\ValidReportStatus;
use Illuminate\Validation\Rule;

class MissingPersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string|min:3|max:50',
                'height' => 'required|numeric|between:1,1000.99',
                'height_unit' => ['required', Rule::in(['feet(ft)', 'centimeter(cm)'])],
                'weight' => 'required|numeric|between:1,1000.99',
                'weight_unit' => ['required', Rule::in(['kilogram(kg)', 'pound(lbs)'])],
                'age' => 'integer|between:0,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:120',
                'important_information' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:60',
                'contact_information' => 'required|string|min:3|max:120',
                'picture' =>  'required|mimes:jpeg,png|max:3000',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'name' => 'required|string|min:3|max:50',
                'height' => 'required|numeric|between:1,9.99',
                'height_unit' => ['required', Rule::in(['feet(ft)', 'centimeter(cm)'])],
                'weight' => 'required|numeric|between:1,120.99',
                'weight_unit' => ['required', Rule::in(['kilogram(kg)', 'pound(lbs)'])],
                'age' => 'integer|between:0,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:120',
                'important_information' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:60',
                'contact_information' => 'required|string|min:3|max:120',
                'picture' =>  'mimes:jpeg,png|max:3000',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
            ];
        }
    }

    public function getData() {
        $data = $this->only(['name', 'height', 'height_unit', 'weight', 'weight_unit', 'age', 'eyes', 'hair', 'unique_sign',
            'important_information', 'last_seen', 'contact_information', 'report_type']);
        return $data;
    }

}
