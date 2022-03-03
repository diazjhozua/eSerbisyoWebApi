<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class MissingItemRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'item' => 'required|string|min:3|max:120',
                'contact_user_id' => 'exists:users,id',
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:250',
                'email' => 'required|max:30|email:rfc,dns',
                'phone_no' => 'required|numeric',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
                'picture' => 'required|mimes:jpeg,png|max:3000',
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'item' => 'required|string|min:3|max:120',
                'contact_user_id' => 'exists:users,id',
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:250',
                'email' => 'required|max:30|email',
                'phone_no' => 'required|numeric',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
               'picture' => 'mimes:jpeg,png|max:3000',
            ];
        }
    }

    public function getData() {
        $data = $this->only(['item', 'contact_user_id','last_seen', 'description', 'weight', 'email', 'phone_no', 'report_type']);
        return $data;
    }
}
