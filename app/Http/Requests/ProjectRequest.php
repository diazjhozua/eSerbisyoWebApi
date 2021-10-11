<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query) {
                return $query->where('model_type', 'Project');
            })],
            'name' => ['required', 'string', 'min:6', 'max:150'],
            'description' => ['required', 'string', 'min:6', 'max:250'],
            'cost' => ['required'],


        ];
        // if ($this->isMethod('POST')) {
        //     return [
        //     'name'=> 'required|string|min:4|max:250',
        //     'description'=> 'required|string|min:4|max:60',
        //     'cost'=> 'required',
        //     'project_start' => 'required|date|date_format:Y-m-d',
        //     'project_end' => 'required|date|date_format:Y-m-d',
        //     'location'=> 'required|string|min:4|max:60',
        //     'pdf' => 'required|mimes:pdf|max:10000',
        //     ];
        // }

        // if ($this->isMethod('PUT')) {
        //     return [
        //         'name'=> 'required|string|min:4|max:60',
        //         'description'=> 'required|string|min:4|max:60',
        //         'cost'=> 'required',
        //         'project_start' => 'required|date|date_format:Y-m-d',
        //         'project_end' => 'required|date|date_format:Y-m-d',
        //         'location'=> 'required|string|min:4|max:60',
        //         'pdf' => 'required|mimes:pdf|max:10000',
        //     ];
        // }
    }
}
