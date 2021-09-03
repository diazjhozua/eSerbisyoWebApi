<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class DocumentRequest extends FormRequest
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
            'document_type_id' => 'required|integer|exists:document_types,id',
            'description' => 'required|string|min:4|max:250',
            'year' => 'required|integer|digits:4|min:1900|max:'.(date('Y')+1),
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
