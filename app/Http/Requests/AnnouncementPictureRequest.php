<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;

class AnnouncementPictureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'announcement_id' => 'required|exists:announcements,id',
            'picture' => 'required|mimes:jpeg,png|max:3000',
        ];
    }

    public function getData() {
        return $this->only(['announcement_id']);
    }
}
