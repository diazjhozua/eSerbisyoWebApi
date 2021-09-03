<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Models\Feedback;

class FeedbackRequest extends FormRequest
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
        return [
            'is_anonymous' => 'required|integer|digits_between: 0,1',
            'feedback_type_id' => 'required|exists:feedback_types,id',
            'message' => 'required|string|min:5|max:255',
        ];
    }
}
