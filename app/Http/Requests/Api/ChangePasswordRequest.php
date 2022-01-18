<?php

namespace App\Http\Requests\Api;

use App\Rules\ApiMatchOldPassword;
use App\Http\Requests\Api\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => ['required', new ApiMatchOldPassword],
            'new_password' => ['required', 'min:8', 'max:16'],
            'new_confirm_password' => ['same:new_password'],
        ];
    }
}
