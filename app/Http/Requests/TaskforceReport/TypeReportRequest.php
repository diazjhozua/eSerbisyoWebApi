<?php

namespace App\Http\Requests\TaskforceReport;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TypeReportRequest extends FormRequest
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

        $date_start = $this->route('date_start');
        $date_end = $this->route('date_start');
        $sort_column = $this->route('sort_column');
        $sort_option = $this->route('sort_option');

        $title = '';
        $date1 = Carbon::createFromFormat('Y-m-d', $date_start);
        $date2 = Carbon::createFromFormat('Y-m-d', $date_end);

        $result = $date1->gt($date2);

        if ($result) {
            $title = 'Error Input';
            $description = 'Invalid Time Range Data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $columnList = ['id', 'name', 'count', 'created_at', 'updated_at'];

        if(!in_array($sort_column, $columnList))
        {
            $title = 'Error Input';
            $description = 'Invalid Column Selected';
            return view('errors.404Report', compact('title', 'description'));
        }

        $columnList = ['ASC', 'DESC'];

        if(!in_array($sort_option, $columnList))
        {
            $title = 'Error Input';
            $description = 'Invalid Column Selected';
            return view('errors.404Report', compact('title', 'description'));
        }
    }
}
