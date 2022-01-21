<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|profanity|string|min:3|max:60',
        ];
    }
}
