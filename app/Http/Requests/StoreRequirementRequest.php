<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequirementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'certificate_id' => ['required', 'exists:certificates,id'],
            'requirement_id' => ['required', 'exists:requirements,id', Rule::unique('certificate_requirement')->where(function ($query) {
                return $query->where('certificate_id', $this->certificate_id)->where('requirement_id',$this->requirement_id);
            })],
        ];
    }
}
