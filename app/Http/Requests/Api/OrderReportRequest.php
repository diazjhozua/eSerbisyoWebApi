<?php

namespace App\Http\Requests\Api;


use App\Http\Requests\Api\FormRequest;

class OrderReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|string|min:5|max:200',
        ];
    }
}
