<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        $rules = [
            'name' => 'required|string|min:1|max:60',
            'term_id' => 'required|integer|exists:terms,id',
            'position_id' => 'required|integer|exists:positions,id',
            'description' => 'required|string|min:4|max:250'
        ];

        if ($this->isMethod('POST')) {
            $rules['picture'] = 'required|mimes:jpeg,png|max:3000';
        }

        if ($this->isMethod('PUT')) {
            $rules['picture'] = 'mimes:jpeg,png|max:3000';
        }
        return $rules;
    }

    public function getData() {
        $data = $this->only(['name', 'term_id', 'position_id', 'description']);
        return $data;
    }
}
