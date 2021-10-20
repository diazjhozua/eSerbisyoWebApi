<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class PositionRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|min:6|max:200',
            'ranking' => 'required|numeric|min:0|not_in:0',
            'job_description' => 'required|string|min:6|max:250'
        ];

        return $rules;
    }
}
