<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\ValidReportStatus;

class ComplaintRequest extends FormRequest
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
            'complaint_type_id' => 'integer|exists:complaint_types,id',
            'custom_type' => 'string|min:1|max:60',
            'reason' => 'required:string|min:4|max:500',
            'action' => 'required:string|min:4|max:500',
            'status' => ['integer', new ValidReportStatus],
            'complainant_list' => 'required|array|min:3',
            // 'complainant_list.complaint_id' => 'required|integer|exists:complaints,id',
            'complainant_list.*.name' => 'required|string|min:1|max:60',
            'complainant_list.*.signature' => 'required|mimes:jpeg,png|max:3000',
            'defendant_list' => 'required|array|min:2',
            // 'defendant_list.complaint_id' => 'required|integer|exists:complaints,id',
            'defendant_list.*.name' => 'required|string|min:1|max:60',
        ];
    }
}
