<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\ValidReportType;
use App\Rules\ValidReportStatus;

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
                'height' => 'required|numeric|between:1,9.99',
                'weight' => 'required|numeric|between:1,120.99',
                'age' => 'integer|between:0,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:120',
                'important_information' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:60',
                'contact_information' => 'required|string|min:3|max:120',
                'picture' =>  'required|mimes:jpeg,png|max:3000',
                'report_type' => ['required', 'integer', new ValidReportType],
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'name' => 'required|string|min:3|max:50',
                'height' => 'required|numeric|between:1,9.99',
                'weight' => 'required|numeric|between:1,120.99',
                'age' => 'integer|between:0,200',
                'eyes' => 'string|min:3|max:50',
                'hair' => 'string|min:3|max:50',
                'unique_sign' => 'required|string|min:3|max:120',
                'important_information' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:60',
                'contact_information' => 'required|string|min:3|max:120',
                'picture' =>  'required|mimes:jpeg,png|max:3000',
                'report_type' => ['required', 'integer', new ValidReportType],
                'status' => 'required', 'integer', new ValidReportStatus,
            ];
        }


    }
}
