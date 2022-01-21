<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class UserRequirementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requirement_id = $this->get('requirement_id');
        return [
            'requirement_id' => ['required', Rule::unique('user_requirements', 'requirement_id')->where(function ($query) use ($requirement_id) {
                return $query->where('requirement_id', $requirement_id)->where('user_id', auth('api')->user()->id);
            })],
            'picture' => 'required|base64image',
        ];
    }
}
