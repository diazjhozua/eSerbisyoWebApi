<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'max:30', 'email'],
            'password' => ['required', 'min:8', 'max:16'],
        ];
    }
}
