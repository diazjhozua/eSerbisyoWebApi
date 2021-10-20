<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class TermRequest extends FormRequest
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
            'name' => 'required|string|min:6|max:150|unique:terms',
            'year_start' => 'required|integer|digits:4|min:1900|max:'.(date('Y')+10),
            'year_end' => 'required|integer|digits:4|min:1900|max:'.(date('Y')+10).'|gt:year_start',
        ];
    }
}
