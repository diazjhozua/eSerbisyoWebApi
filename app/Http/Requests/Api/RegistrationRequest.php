<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'max:150', 'email' , 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:16'],
            'password_confirmation' => ['same:password'],
        ];
    }
}
