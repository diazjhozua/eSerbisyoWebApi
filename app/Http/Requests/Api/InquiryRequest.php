<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'about' => 'required|string|min:5|max:80',
            'message' => 'required|string|min:5|max:500',
        ];
    }
}
