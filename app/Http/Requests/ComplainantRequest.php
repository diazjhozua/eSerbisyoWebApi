<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class ComplainantRequest extends FormRequest
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
            'complaint_id' => 'required|integer|exists:complaints,id',
            'name' => 'required|string|min:5|max:150|unique:complainants,name'.$this->complainant_id
        ];
    }
}
