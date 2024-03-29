<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class DefendantRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

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
