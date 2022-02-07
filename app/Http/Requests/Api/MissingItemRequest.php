<?php

namespace App\Http\Requests\Api;

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
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:250',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
                'picture' =>  'required|base64image',
                'credential' =>  'required|base64image',
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'item' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:250',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'report_type' => ['required', Rule::in(['Missing', 'Found'])],
                'picture' =>  'base64image',
                'credential' =>  'base64image',
            ];
        }
    }

    public function getData() {
        $data = $this->only(['item', 'contact_user_id','last_seen', 'description', 'weight', 'email', 'phone_no', 'report_type']);
        return $data;
    }
}
