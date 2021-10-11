<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\RespondReportStatusRule;
use Illuminate\Validation\Rule;

class RespondReportRequest extends FormRequest
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
            'status' => ['required', Rule::in(['Invalid', 'Noted'])],
            'admin_message' => 'required|string|min:6|max:250',
        ];
    }
}
