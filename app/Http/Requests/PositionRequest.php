<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class PositionRequest extends FormRequest
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
            'position' => 'required|string|unique:positions|min:5|max:60',
        ];

        if ($this->isMethod('POST')) {
            $rules['id'] = 'required|integer|unique:positions';
        }

        if ($this->isMethod('PUT')) {
            $rules['id'] = 'required|integer|exists:positions,id';
        }

        return $rules;
    }
}
