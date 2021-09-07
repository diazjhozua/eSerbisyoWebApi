<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class ComplainantRequest extends FormRequest
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
            $complaint_id = $this->get('complaint_id');
            return [
                'complaint_id' => 'required|integer|exists:complaints,id',
                'name' => ['required', 'string', 'min:5', 'max:150', Rule::unique('complainants', 'name')->where(function ($query) use ($complaint_id) {
                    return $query->where('complaint_id', $complaint_id);
                })],
                'signature' => 'required|mimes:jpeg,png|max:3000',
            ];
        }

        if ($this->isMethod('PUT')) {
            $complaint_id = $this->get('complaint_id');
            $id = $this->get('id');
            dd($id);
            return [
                'id' => 'required|integer|exists:complainants,id',
                'signature' => 'sometimes|mimes:jpeg,png|max:3000',
            ];
        }
    }
}
