<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\BooleanRule;
use App\Rules\OneOf;
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
            'type_id' => ['required_without:custom_type', new OneOf($this, ["type_id", "custom_type"]), Rule::exists('types', 'id')->where(function ($query) {
                return $query->where('model_type', 'Feedback');
            })],
            'custom_type' => ['required_without:type_id', new OneOf($this, ["type_id", "custom_type"]), 'string', 'min:4', 'max:60'],
            'polarity' => ['required', Rule::in(['Positive', 'Neutral', 'Negative'])],
            'message' => 'required|string|min:5|max:255',
        ];
    }
}
