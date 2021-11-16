<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ReportTypeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:200', Rule::unique('types')->where(function ($query) {
                return $query->where('model_type', 'Report');
            })],
        ];
    }
}
