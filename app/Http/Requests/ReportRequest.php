<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;

class ReportRequest extends FormRequest
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
                'report_type_id' => 'required_without:custom_type|integer|exists:report_types,id',
                'custom_type' => 'required_without:report_type_id|string|min:5|max:60',
                'location_address' => 'required|string|min:10|max:60',
                'landmark' => 'required|string|min:10|max:60',
                'description' => 'required|string|min:10|max:250',
                'picture' =>  'mimes:jpeg,png|max:3000',
                'is_anonymous' => ['required', 'integer', new BooleanRule],
                'is_urgent' => ['required', 'integer', new BooleanRule],
            ];
        }
    }
}
