<?php

namespace App\Http\Requests;


use App\Http\Requests\Api\FormRequest;

class AndroidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules =  [
            'version' => 'required|string|min:6|max:25|unique:androids',
            'description' => 'required|string|min:6|max:500',
        ];

        if ($this->isMethod('POST')) {
            $rules['apk'] = 'required|max:60000';
        }

        if ($this->isMethod('PUT')) {
            $rules['apk'] = 'max:60000';
        }

        return $rules;
    }

    public function getData() {
        $data = $this->only(['version', 'description']);
        return $data;
    }
}

