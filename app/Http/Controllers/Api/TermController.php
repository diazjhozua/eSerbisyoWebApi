<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermRequest;
use App\Http\Resources\TermResource;
use App\Models\Employee;
use App\Models\Term;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::withCount('employees')->orderBy('year_end','DESC')->get();
        $terms->add(new Term([ 'id' => 0, 'name' => 'Others (No term ID)', 'year_start' => NULL, 'year_end' => NULL, 'created_at' => now(), 'updated_at' => now(),
            'employees_count' => Employee::where('term_id', NULL)->count() ]));
        return TermResource::collection($terms)->additional(['success' => true]);
    }

    public function store(TermRequest $request)
    {
        $term = Term::create($request->validated());
        $term->employees_count = 0;
        return (new TermResource($term))->additional(Helper::instance()->storeSuccess('term'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $employees = Employee::with('position')->where('term_id', NULL)->orderBy('created_at', 'DESC')->get();
            $term = new Term([ 'id' => 0, 'name' => 'Others (No term ID)', 'year_start' => NULL, 'year_end' => NULL, 'created_at' => now(), 'updated_at' => now(),
            'employees_count' => $employees->count(), 'others' => $employees]);
        } else {  $term = Term::with('employees.position')->withCount('employees')->findOrFail($id); }
        return (new TermResource($term))->additional(Helper::instance()->itemFound('term'));
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $term = Term::findOrFail($id);
        return (new TermResource($term))->additional(Helper::instance()->itemFound('term'));
    }

    public function update(TermRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $term = Term::withCount('employees')->findOrFail($id);
        $term->fill($request->validated())->save();
        return (new TermResource($term))->additional(Helper::instance()->updateSuccess('term'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $term = Term::findOrFail($id);
        Employee::where('term_id', $term->id)->update(['term_id' => NULL, 'custom_term' => 'deleted type: '.$term->name]);
        $term->delete();
        return response()->json(Helper::instance()->destroySuccess('term'));
    }
}
