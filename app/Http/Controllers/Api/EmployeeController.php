<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\TermResource;
use App\Http\Resources\PositionResource;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Term;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::with('position', 'term')->orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'documents' => EmployeeResource::collection($employees)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // load all positions and terms to select the user
        $positions = Position::get();
        $terms = Term::get();

        return response()->json([
            'success' => true,
            'positions' => PositionResource::collection($positions),
            'terms' => TermResource::collection($terms),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|min:1|max:60',
            'term_id' => 'required|integer|exists:terms,id',
            'position_id' => 'required|integer|exists:terms,id',
            'description' => 'required|string|min:4|max:250',
            'picture' => 'required|mimes:jpeg,png|max:3000'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->term_id = $request->term_id;
        $employee->position_id = $request->position_id;

        $fileName = time().'_'.$request->picture->getClientOriginalName();
        $filePath = $request->file('picture')->storeAs('employees', $fileName, 'public');
        $employee->picture_name = $fileName;
        $employee->file_path = $filePath;

        $employee->description = $request->description;
        $employee->save();

        return response()->json([
            'success' => true,
            'message' => 'New employee created succesfully',
            'employee' => new EmployeeResource($employee->load('term', 'position'))
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $employee = Employee::with('term', 'position')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found employee data',
                'employee' => new EmployeeResource($employee)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No employee id found',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $employee = Employee::with('term', 'position')->findOrFail($id);
            $positions = Position::get();
            $terms = Term::get();

            return response()->json([
                'success' => true,
                'message' => 'Found employee data',
                'employee' => new EmployeeResource($employee),
                'positions' => PositionResource::collection($positions),
                'terms' => TermResource::collection($terms),
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No employee id found',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required|string|min:1|max:60',
            'term_id' => 'required|integer|exists:terms,id',
            'position_id' => 'required|integer|exists:terms,id',
            'description' => 'required|string|min:4|max:250',
            'picture' => 'mimes:jpeg,png|max:3000'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        try {
            $employee = Employee::with('term', 'position')->findOrFail($id);
            $employee->name = $request->name;
            $employee->term_id = $request->term_id;
            $employee->position_id = $request->position_id;

            //check if they want to update the pdf file
            if($request->hasFile('picture')) {
                Storage::delete('public/employees/'. $employee->picture_name);

                $fileName = time().'_'.$request->picture->getClientOriginalName();
                $filePath = $request->file('picture')->storeAs('employees', $fileName, 'public');

                $employee->picture_name = $fileName;
                $employee->file_path = $filePath;
            }

            $employee->description = $request->description;
            $employee->save();

            return response()->json([
                'success' => true,
                'message' => 'The employee is successfully updated',
                'employee' => new EmployeeResource($employee)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No employee id found',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            Storage::delete('public/employees/'. $employee->picture_name);
            $employee->delete();
            return response()->json([
                'success' => true,
                'message' => 'The employee is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No employee id found',
            ]);
        }
    }
}
