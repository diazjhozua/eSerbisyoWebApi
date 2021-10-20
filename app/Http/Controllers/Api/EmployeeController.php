<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\PositionResource;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Term;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('position', 'term')->orderBy('created_at','DESC')->get();
        return EmployeeResource::collection($employees)->additional(['success' => true]);
    }

    public function create()
    {
        $positions = Position::get();
        $terms = Term::get();
        return ['positions' => PositionResource::collection($positions), 'terms' => PositionResource::collection($terms), 'success' => true] ;
    }

    public function store(EmployeeRequest $request)
    {

        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('employees', $fileName, 'public');
        $employee = Employee::create(array_merge($request->getData(), ['picture_name' => $fileName,'file_path' => $filePath]));

        return (new EmployeeResource($employee->load('term', 'position')))->additional(Helper::instance()->storeSuccess('employee'));
    }

    public function show(Employee $employee)
    {
        return (new EmployeeResource($employee->load('term', 'position')))->additional(Helper::instance()->itemFound('employee'));
    }

    public function edit(Employee $employee)
    {
        $positions = Position::get();
        $terms = Term::get();
        return (new EmployeeResource($employee->load('term', 'position')))->additional(array_merge(['positions' => PositionResource::collection($positions), 'terms' => PositionResource::collection($terms)],Helper::instance()->itemFound('employee')));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        if($request->hasFile('picture')) {
            Storage::delete('public/employees/'. $employee->picture_name);
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('employees', $fileName, 'public');
            $employee->fill(array_merge($request->getData(), ['custom_term' => NULL, 'custom_position' => NULL,'picture_name' => $fileName,'file_path' => $filePath]))->save();
        } else {  $employee->fill(array_merge($request->getData(), ['custom_term' => NULL, 'custom_position' => NULL]))->save(); }
        return (new EmployeeResource($employee->load('term', 'position')))->additional(Helper::instance()->updateSuccess('employee'));
    }

    public function destroy(Employee $employee)
    {
        Storage::delete('public/employees/'. $employee->picture_name);
        $employee->delete();
        return response()->json(Helper::instance()->destroySuccess('employee'));
    }
}
