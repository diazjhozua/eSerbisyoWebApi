<?php

namespace App\Http\Requests\Report;

use App\Http\Requests\Api\FormRequest;
use Illuminate\Validation\Rule;

class AuditLogReportRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date_start' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:date_end'],
            'date_end' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:date_start'],
            'sort_column' => ['required', Rule::in(['id', 'subject_type', 'event', 'created_at'])],
            'sort_option' => ['required', Rule::in(['ASC', 'DESC'])],
        ];
    }
}
