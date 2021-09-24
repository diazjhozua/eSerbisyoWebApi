<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;  
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
        $rules = [
            'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query) {
                return $query->where('model_type', 'Project');
            })],
            'name'=> 'required|string|min:4|max:60',
            'description'=> 'required|string|min:4|max:60',
            'cost'=> 'required',
            'project_start' => 'required|date|date_format:Y-m-d',
            'project_end' => 'required|date|date_format:Y-m-d',
            'location'=> 'required|string|min:4|max:60',
            
        ];

        if ($this->isMethod('POST')) {
            $rules['pdf'] = 'required|mimes:pdf|max:10000';
        }

        if ($this->isMethod('PUT')) {
            $rules['pdf'] = 'mimes:pdf|max:10000';
        }

        return $rules;
    }
       

    
}