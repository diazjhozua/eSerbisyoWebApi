<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use App\Rules\ApiMatchOldEmail;

class ChangeEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_email' => ['required', new ApiMatchOldEmail],
            'new_email' => ['required', 'max:150', 'email' , 'unique:users,email'],
            'new_confirm_email' => ['same:new_email'],
        ];
    }
}
