<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;

class ApiLoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'max:200', 'email'],
            'password' => ['required', 'min:8', 'max:16'],
            'device_id' => ['required'],
        ];
    }
}
