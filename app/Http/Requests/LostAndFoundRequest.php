<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Rules\ValidReportType;
use App\Rules\ValidReportStatus;

class LostAndFoundRequest extends FormRequest
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
                'item' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:120',
                'contact_information' => 'required|string|min:3|max:120',
                'report_type' => 'required','integer', new ValidReportType,
                'picture' => 'required|mimes:jpeg,png|max:3000',
            ];


        }

        if ($this->isMethod('PUT')) {
            return [
                'item' => 'required|string|min:3|max:120',
                'last_seen' => 'required|string|min:3|max:120',
                'description' => 'required|string|min:3|max:120',
                'contact_information' => 'required|string|min:3|max:120',
                'report_type' => 'required','integer', new ValidReportType,
                'picture' => 'required|mimes:jpeg,png|max:3000',
                'status' => 'required', 'integer', new ValidReportStatus,
            ];
        }
    }
}
