<?php

namespace App\Http\Requests;

use App\Rules\BooleanRule;
use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:200'],
            'price' => ['required', 'numeric' , 'between:0,5000.99'],
            'status' => ['required', Rule::in(['Available', 'Unavailable'])],
            'is_open_delivery' => ['required', Rule::in([0, 1])],
        ];
    }
}
