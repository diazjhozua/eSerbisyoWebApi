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

        if ($this->isMethod('POST')) {
            return [
                'complaint_type_id' => 'required_without:custom_type|integer|exists:complaint_types,id',
                'custom_type' => 'required_without:complaint_type_id|string|min:1|max:60',
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
                'complainant_list' => 'required|array|between:1,10',
                'complainant_list.*.name' => 'required|string|distinct|min:1|max:60',
                'complainant_list.*.signature' => 'required|distinct|mimes:jpeg,png|max:3000',
                'defendant_list' => 'required|array|between:1,10',
                'defendant_list.*.name' => 'required|distinct|string|min:1|max:60',
            ];
        }


        if ($this->isMethod('PUT')) {
            return [
                'complaint_type_id' => 'required_without:custom_type|integer|exists:complaint_types,id',
                'custom_type' => 'required_without:complaint_type_id|string|min:1|max:60',
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
                'status' => 'required', 'integer', new ValidReportStatus,
            ];
        }
    }
}
