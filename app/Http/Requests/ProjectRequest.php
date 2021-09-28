<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;
class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
            'name'=> 'required|string|min:4|max:60',
            'description'=> 'required|string|min:4|max:60',
            'cost'=> 'required',
            'project_start' => 'required|date|date_format:Y-m-d',
            'project_end' => 'required|date|date_format:Y-m-d',
            'location'=> 'required|string|min:4|max:60',
            'pdf' => 'required|mimes:pdf|max:10000',
            ];
        }

        if ($this->isMethod('PUT')) {
            return [
                'name'=> 'required|string|min:4|max:60',
                'description'=> 'required|string|min:4|max:60',
                'cost'=> 'required',
                'project_start' => 'required|date|date_format:Y-m-d',
                'project_end' => 'required|date|date_format:Y-m-d',
                'location'=> 'required|string|min:4|max:60',
                'pdf' => 'required|mimes:pdf|max:10000',
            ];
        }
    }
}
