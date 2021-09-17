<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;
use Illuminate\Validation\Rule;

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
            'is_anonymous' => ['required', 'integer', new BooleanRule],
            'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query){
                return $query->where('model_type', 'Feedback');
            })],
            'polarity' => ['required', Rule::in(['Positive', 'Neutral', 'Negative'])],
            'message' => 'required|string|min:5|max:255',
        ];
    }
}
