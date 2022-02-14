<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;

class BikerApplicationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone_no' => 'required|numeric',
            'bike_type' => 'required:string|min:4|max:30',
            'bike_size' => 'required:string|min:4|max:30',
            'bike_color' => 'required:string|min:4|max:30',
            'reason' => 'required:string|min:4|max:250',
            'picture' =>  'required|base64image',
        ];
    }


    public function getData() {
        $data = $this->only(['bike_type', 'bike_size', 'bike_color', 'reason', 'phone_no']);
        return $data;
    }
}
