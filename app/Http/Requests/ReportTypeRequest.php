<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class ReportTypeRequest extends FormRequest
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
        return [
            'type' => 'required|unique:report_types|string|min:4|max:120'
        ];
    }
}
