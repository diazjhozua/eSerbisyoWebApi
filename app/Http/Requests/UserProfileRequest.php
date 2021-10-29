<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class UserProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|min:4|max:150',
            'middle_name' => 'required|string|min:4|max:150',
            'last_name' => 'required|string|min:4|max:150',
            'address' => 'required|string|min:4|max:250',
            'picture' => 'mimes:jpeg,png|max:3000'
        ];
    }

    public function getData() {
        $data = $this->only(['first_name', 'middle_name', 'last_name', 'address']);
        return $data;
    }
}
