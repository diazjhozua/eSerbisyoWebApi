<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\OneOf;
use Illuminate\Validation\Rule;

class ComplaintRequest extends FormRequest
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
                    return $query->where('model_type', 'Complaint');
                })],
                'custom_type' => ['required_without:type_id', new OneOf($this, ["type_id", "custom_type"]), 'string', 'min:4', 'max:60'],
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
                'complainant_list' => 'required|array|between:1,10',
                'complainant_list.*.name' => 'required|string|distinct|min:1|max:60',
                'complainant_list.*.signature' => 'required|distinct|mimes:jpeg,png|max:3000',
                'defendant_list' => 'required|array|between:1,10',
                'defendant_list.*.name' => 'required|distinct|string|min:3|max:60',
            ];
        }


        if ($this->isMethod('PUT')) {
            return [
                'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query) {
                    return $query->where('model_type', 'Complaint');
                })],
                'custom_type' => 'required_without:complaint_type_id|string|<min:3></min:3>|max:60',
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
            ];
        }
    }

    public function getComplaint() {
        $data = $this->only(['type_id', 'custom_type', 'reason', 'action']);
        return $data;
    }

}
