<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\MatchOldEmail;

class ChangeEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_email' => ['required', new MatchOldEmail],
            'new_email' => ['required', 'max:30', 'email' , 'unique:users,email'],
            'new_confirm_email' => ['same:new_email'],
        ];
    }
}
