<?php

namespace App\Http\Controllers\Web\Admin;

use App;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\PositionResource;
use App\Http\Resources\TermResource;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Term;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        if (App::environment('production')) {
            $employeesData =  DB::table('employees')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURRENT_DATE then 1 end) as this_day_count")
                ->first();
        } else {
            $employeesData =  DB::table('employees')
                ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
                ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
                ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
                ->first();
        }



        $employees = Employee::with('position', 'term')->orderBy('created_at','DESC')->get();
        return view('admin.information.employees.index', compact('employeesData', 'employees'));
    }

    public function create()
    {
        $positions = Position::get();
        $terms = Term::get();
        return ['positions' => PositionResource::collection($positions), 'terms' => TermResource::collection($terms), 'success' => true] ;
    }

    public function store(EmployeeRequest $request)
    {
        $fileName = uniqid().'-'.time();
        $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
        $employee = Employee::create(array_merge($request->getData(), ['picture_name' => $result->getPublicId(),'file_path' => $result->getPath()]));

        return (new EmployeeResource($employee->load('term', 'position')))->additional(Helper::instance()->storeSuccess('employee'));
    }

    public function edit(Employee $employee)
    {
        $positions = Position::get();
        $terms = Term::get();
        return (new EmployeeResource($employee->load('term', 'position')))
            ->additional(array_merge(['positions' => PositionResource::collection($positions),
            'terms' => TermResource::collection($terms)],
            Helper::instance()->itemFound('employee')));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        if($request->hasFile('picture')) {
            Cloudinary::destroy($employee->picture_name);
            $fileName = uniqid().'-'.time();
            $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
            $employee->fill(array_merge($request->getData(), ['custom_term' => NULL, 'custom_position' => NULL, 'picture_name' => $result->getPublicId(),'file_path' => $result->getPath()]))->save();
        } else {  $employee->fill(array_merge($request->getData(), ['custom_term' => NULL, 'custom_position' => NULL]))->save(); }
        return (new EmployeeResource($employee->load('term', 'position')))->additional(Helper::instance()->updateSuccess('employee'));
    }

    public function destroy(Employee $employee)
    {
        Cloudinary::destroy($employee->picture_name);
        $employee->delete();
        return response()->json(Helper::instance()->destroySuccess('employee'));
    }
}
