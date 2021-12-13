<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\OneOf;
use Illuminate\Validation\Rule;
use Log;

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
                'contact_user_id' => 'exists:users,id',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
                'complainant_list' => 'required|array|between:1,10',
                'complainant_list.*.name' => 'required|string|distinct|min:1|max:60',
                'complainant_list.*.signature' => 'required|distinct|base64image',
                'defendant_list' => 'required|array|between:1,10',
                'defendant_list.*.name' => 'required|distinct|string|min:3|max:60',
            ];
        }


        if ($this->isMethod('PUT')) {
            return [
                'type_id' => ['required_without:custom_type', new OneOf($this, ["type_id", "custom_type"]), Rule::exists('types', 'id')->where(function ($query) {
                    return $query->where('model_type', 'Complaint');
                })],
                'custom_type' => ['required_without:type_id', new OneOf($this, ["type_id", "custom_type"]), 'string', 'min:4', 'max:60'],
                'contact_user_id' => 'exists:users,id',
                'email' => 'required|max:150|email',
                'phone_no' => 'required|numeric',
                'reason' => 'required:string|min:4|max:500',
                'action' => 'required:string|min:4|max:500',
            ];
        }
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('POST')) {

            $complainant_list = $this->get('complainant_list');

            $decoded_complainant_list = [];
            foreach ($complainant_list as $complainant)  {

                array_push($decoded_complainant_list,  (array) json_decode($complainant, false));
            }

            $defendant_list = $this->get('defendant_list');

            $decoded_defendant_list = [];
            foreach ($defendant_list as $defendant)  {
                array_push($decoded_defendant_list, (array) json_decode($defendant));
            }

            $this->merge([
                'complainant_list' => $decoded_complainant_list,
                'defendant_list' => $decoded_defendant_list,
            ]);

            Log::debug($this->request->all());

            Log::debug($this->get('complainant_list'));
        }
    }

    public function getData() {
        $data = $this->only(['type_id', 'contact_user_id', 'email', 'phone_no',  'custom_type', 'reason', 'action']);
        return $data;
    }

}
