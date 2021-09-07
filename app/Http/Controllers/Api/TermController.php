<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\TermResource;
use App\Models\Term;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = Term::withCount('employees')->orderBy('year_end','DESC')->get();
        return response()->json([
            'success' => true,
            'terms' => TermResource::collection($terms)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermRequest $request)
    {
        $term = new Term();
        $term->term = $request->term;
        $term->year_start = $request->year_start;
        $term->year_end = $request->year_end;
        $term->save();
        $term->employees_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New term created succesfully',
            'term' => new TermResource($term)
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
        //show all employees on specific term id

        try {
            $term = Term::with('employees.position')->withCount('employees')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found term type data',
                'term' => new TermResource($term)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('term'));
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
            $term = Term::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found term data',
                'term' => new TermResource($term)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('term'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TermRequest $request, $id)
    {
        try {
            $term = Term::withCount('employees')->findOrFail($id);
            $term->term = $request->term;
            $term->year_start = $request->year_start;
            $term->year_end = $request->year_end;
            $term->save();

            return response()->json([
                'success' => true,
                'message' => 'The term is successfully updated',
                'document_type' => new TermResource($term)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('term'));
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
            $term = Term::findOrFail($id);
            $term->delete();

            return response()->json([
                'success' => true,
                'message' => 'The term is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('term'));
        }
    }
}
