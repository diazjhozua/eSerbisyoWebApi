<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class UserVerificationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['Approved', 'Denied'])],
            'admin_message' => ['required', 'string', 'min:4', 'max:250'],
        ];
    }
}
