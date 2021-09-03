<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdinanceCategoryRequest;
use App\Models\OrdinanceCategory;
use App\Http\Resources\OrdinanceCategoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdinanceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ordinance_categories = OrdinanceCategory::withCount('ordinances')->orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'ordinance_categories' => OrdinanceCategoryResource::collection ($ordinance_categories)
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
    public function store(OrdinanceCategoryRequest $request)
    {
        $ordinance_category = new OrdinanceCategory();
        $ordinance_category->category = $request->category;
        $ordinance_category->save();
        $ordinance_category->ordinances_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New ordinance category created succesfully',
            'ordinance_category' => new OrdinanceCategoryResource($ordinance_category)
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
            $ordinance_category = OrdinanceCategory::with('ordinances')->withCount('ordinances')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found ordinance category data',
                'ordinance_category' => new OrdinanceCategoryResource($ordinance_category)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('ordinance category'));
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
            $ordinance_category = OrdinanceCategory::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found ordinance type data',
                'ordinance_category' => new OrdinanceCategoryResource($ordinance_category)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('ordinance category'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdinanceCategoryRequest $request, $id)
    {
        try {
            $ordinance_category = OrdinanceCategory::withCount('ordinances')->findOrFail($id);
            $ordinance_category->category = $request->category;
            $ordinance_category->save();

            return response()->json([
                'success' => true,
                'message' => 'The ordinance category is successfully updated',
                'ordinance_category' => new OrdinanceCategoryResource($ordinance_category)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('ordinance category'));
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
            $category = OrdinanceCategory::findOrFail($id);
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'The ordinance category is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('ordinance category'));
        }
    }
}
