<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query) {
                return $query->where('model_type', 'Document');
            })],
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

    public function getData() {
        $data = $this->only(['type_id', 'description', 'year']);
        return $data;
    }
}
