<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class OrdinanceRequest extends FormRequest
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
            'ordinance_no'=> 'required|string|min:4|max:60',
            'title'=> 'required|string|min:4|max:60',
            'date_approved' => 'required',
            'ordinance_category_id' => 'required|integer|exists:ordinance_categories,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['pdf'] = 'required|mimes:pdf|max:10000';
        }

        if ($this->isMethod('PUT')) {
            $rules['pdf'] = 'mimes:pdf|max:10000';
        }

        return $rules;
    }
}
