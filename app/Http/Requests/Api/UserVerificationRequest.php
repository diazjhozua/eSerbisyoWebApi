<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;

class UserVerificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'picture' => 'required|base64image'
        ];
    }
}
