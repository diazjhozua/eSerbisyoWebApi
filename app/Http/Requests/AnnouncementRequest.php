<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class AnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type_id' => ['required', Rule::exists('types', 'id')->where(function ($query) {
                return $query->where('model_type', 'Announcement');
            })],
            'title' => 'required|string|min:4|max:120',
            'description' => 'required|string|min:10|max:63,206',
            'picture_list' => 'array',
            'picture_list.*.picture' => 'required|distinct|mimes:jpeg,png|max:3000',
        ];
    }

    public function getData() {
        return $this->only(['type_id', 'title', 'description']);
    }
}
