<?php

namespace App\Http\Requests;

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
            'first_name' => 'required|string|min:4|max:150',
            'middle_name' => 'string|min:4|max:150',
            'last_name' => 'required|string|min:4|max:150',
            'purok_id' => ['required', Rule::exists('puroks', 'id')],
            'email' => ['required', 'max:120', 'email:rfc,dns' , 'unique:users,email' ],
            'password' => ['required', 'min:8', 'max:16'],
            'password_confirmation' => ['same:password'],
        ];
    }

    public function getData() {
        $data = $this->only(['first_name', 'middle_name', 'last_name', 'purok_id', 'email']);
        return $data;
    }
}
