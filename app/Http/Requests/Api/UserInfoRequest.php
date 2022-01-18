<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class UserInfoRequest extends FormRequest
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
            'address' => 'required|string|min:4|max:250',
            'picture' => 'base64image'
        ];
    }

    public function getData() {
        $data = $this->only(['first_name', 'middle_name', 'last_name', 'address', 'purok_id']);
        return $data;
    }
}
