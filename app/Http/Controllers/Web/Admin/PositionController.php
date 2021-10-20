<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Employee;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::withCount('employees')->orderBy('ranking', 'ASC')->get();
        $positions->add(new Position([ 'id' => 0, 'name' => 'Officials with no specified position', 'created_at' => now(), 'updated_at' => now(),
            'employees_count' => Employee::where('position_id', NULL)->count() ]));

        return view('admin.positions.index')->with('positions', $positions);
    }

    public function store(PositionRequest $request)
    {
        $position = Position::create($request->validated());
        $position->employees_count = 0;
        return (new PositionResource($position))->additional(Helper::instance()->storeSuccess('position'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $employees = Employee::with('term')->where('position_id', NULL)->orderBy('created_at', 'DESC')->get();
            $position = new Position([ 'id' => 0, 'name' => 'Officials with no specified position', 'created_at' => now(), 'updated_at' => now(),
            'employees_count' => $employees->count(), 'employees' => $employees]);
        } else {  $position = Position::with('employees.term')->withCount('employees')->findOrFail($id); }
        return view('admin.positions.show')->with('position', $position);
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $position = Position::findOrFail($id);
        return (new PositionResource($position))->additional(Helper::instance()->itemFound('position'));
    }

    public function update(PositionRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $position = Position::withCount('employees')->findOrFail($id);
        $position->fill($request->validated())->save();
        return (new PositionResource($position))->additional(Helper::instance()->updateSuccess('position'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $position = Position::findOrFail($id);
        Employee::where('position_id', $position->id)->update(['position_id' => NULL, 'custom_position' => 'deleted position: '.$position->name]);
        $position->delete();
        return response()->json(Helper::instance()->destroySuccess('position'));
    }
}
