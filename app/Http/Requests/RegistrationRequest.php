<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|min:4|max:150',
            'middle_name' => 'string|min:4|max:150',
            'last_name' => 'required|string|min:4|max:150',
            'email' => ['required', 'max:30', 'email' , 'unique:users,email'],
            'password' => ['required', 'min:8', 'max:16'],
            'password_confirmation' => ['same:password'],
        ];
    }

    public function getData() {
        $data = $this->only(['first_name', 'middle_name', 'last_name', 'email']);
        return $data;
    }
}
