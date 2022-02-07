<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class OrdinanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'ordinance_no'=> 'required|string|min:4|max:60',
            'title'=> 'required|string|min:4|max:250',
            'date_approved' => 'required|date|date_format:Y-m-d',
            'type_id' => 'required|integer|exists:types,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['pdf'] = 'required|mimes:pdf|max:10000';
        }

        if ($this->isMethod('PUT')) {
            $rules['pdf'] = 'mimes:pdf|max:10000';
        }

        return $rules;
    }

    public function getData() {
        $data = $this->only(['ordinance_no', 'date_approved', 'type_id']);
        return $data;
    }
}
