<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class OrdinanceCategoryRequest extends FormRequest
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
        return [
            'category' => 'required|unique:ordinance_categories|string|min:4|max:120',
        ];
    }
}
