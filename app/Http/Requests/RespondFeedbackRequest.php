<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class RespondFeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'admin_respond' => ['required', 'string', 'min:4', 'max:250'],
        ];
    }
}
