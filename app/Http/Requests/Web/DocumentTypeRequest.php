<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:200', Rule::unique('types')->where(function ($query) {
                return $query->where('model_type', 'Document');
            })],
        ];
    }
}
