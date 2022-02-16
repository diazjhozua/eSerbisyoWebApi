<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class OrderApplicationAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'application_status' => ['required', Rule::in(['Approved', 'Denied'])],
            'pickup_date' => ['date', 'date_format:Y-m-d', 'after:today'],
            'admin_message' => ['string', 'min:4', 'max:250'],
        ];
    }

    public function getData() {
        $data = $this->only(['application_status', 'pickup_date', 'admin_message']);
        return $data;
    }

}
