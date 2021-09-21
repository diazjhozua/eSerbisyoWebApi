<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;
use App\Rules\OneOf;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
                'type_id' => ['required_without:custom_type', new OneOf($this, ["type_id", "custom_type"]), Rule::exists('types', 'id')->where(function ($query) {
                    return $query->where('model_type', 'Report');
                })],
                'custom_type' => ['required_without:type_id', new OneOf($this, ["type_id", "custom_type"]), 'string', 'min:4', 'max:60'],
                'location_address' => 'required|string|min:10|max:60',
                'landmark' => 'required|string|min:10|max:60',
                'description' => 'required|string|min:10|max:250',
                'picture' =>  'mimes:jpeg,png|max:3000',
                'is_anonymous' => ['required', 'integer', new BooleanRule],
                'urgency_classification' => ['required', Rule::in(['Nonurgent', 'Urgent'])],
            ];
        }
    }

    public function getData() {
        $data = $this->only(['type_id', 'custom_type', 'location_address', 'landmark', 'description', 'is_anonymous', 'urgency_classification']);
        return $data;
    }
}
