<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedbackTypeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:4', 'max:150', Rule::unique('types')->where(function ($query) {
                return $query->where('model_type', 'Feedback');
            })],
        ];
    }
}
