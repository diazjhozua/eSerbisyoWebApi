<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ChangeUserStatusRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['Enable', 'Disable'])],
            'admin_status_message' => ['required', 'string', 'min:4', 'max:250'],
        ];
    }
}
