<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class RequirementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:200', 'unique:requirements,name'],
        ];
    }
}
