<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class DefendantRequest extends FormRequest
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
                'name' => ['required', 'string', 'min:5', 'max:150', Rule::unique('defendants', 'name')->where(function ($query) use ($complaint_id) {
                    return $query->where('complaint_id', $complaint_id);
                })],
            ];
        }

        if ($this->isMethod('PUT')) {
            $complaint_id = $this->get('complaint_id');
            $id = $this->get('id');
            return [
                'complaint_id' => 'required|integer|exists:complaints,id',
                'name' => ['required', 'string', 'min:5', 'max:150', Rule::unique('defendants', 'name')->where(function ($query) use ($complaint_id) {
                    return $query->where('complaint_id', $complaint_id);
                })->ignore($id)],
            ];
        }
    }
}
