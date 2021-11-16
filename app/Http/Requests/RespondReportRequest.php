<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\RespondReportStatusRule;
use Illuminate\Validation\Rule;

class RespondReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;

    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['Invalid', 'Noted'])],
            'admin_message' => 'required|string|min:6|max:250',
        ];
    }
}
