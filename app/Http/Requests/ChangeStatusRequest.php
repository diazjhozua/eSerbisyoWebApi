<?php

namespace App\Http\Requests;


use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ChangeStatusRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['Pending', 'Denied', 'Approved', 'Resolved'])],
        ];
    }
}
