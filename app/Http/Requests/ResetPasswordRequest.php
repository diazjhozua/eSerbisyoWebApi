<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => ['required', Rule::exists('password_resets', 'token')->where(function ($query) {
                return $query->where('email', $this->email)->where('token',$this->token);
            })],
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'min:8', 'max:16'],
            'password_confirmation' => ['same:password'],
        ];
    }
}
