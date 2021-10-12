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
            'cost' => ['required', 'min:1', 'max:999999999999'],
            'project_start' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:project_end'],
            'project_end' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:project_start'],
            'location' => ['required', 'string', 'min:4', 'max:250'],
        ];

        if ($this->isMethod('POST')) {
            $rules['pdf'] = 'required|mimes:pdf|max:10000';
        }

        if ($this->isMethod('PUT')) {
            $rules['pdf'] = 'mimes:pdf|max:10000';
        }

        return $rules;
    }

    public function getData() {
        return $this->only(['type_id', 'name', 'description', 'cost', 'project_start', 'project_end', 'location']);
    }
}
