<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ordinance;
use App\Models\OrdinanceCategory;
use App\Http\Resources\OrdinanceResource;
use App\Http\Resources\OrdinanceCategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrdinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ordinances = Ordinance::with('ordinance_category')->orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'ordinances' => OrdinanceResource::collection($ordinances)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ordinance_categories = OrdinanceCategory::get();

        return response()->json([
            'success' => true,
            'ordinance_category' => OrdinanceCategoryResource::collection($ordinance_categories)
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

            'ordinance_no'=> 'required|integer',
            'title'=> 'required|string|min:1|max:60',
            'date_approved' => 'required',
            'ordinance_category_id' => 'required|integer|exists:ordinance_categories,id',
            'pdf' => 'required|mimes:pdf|max:10000'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }
            $ordinance = new Ordinance();
            $ordinance->ordinance_no = $request->ordinance_no;
            $ordinance->title = $request->title;
            $ordinance->date_approved = $request->date_approved;
            $ordinance->ordinance_category_id = $request->ordinance_category_id;
           
    
            $fileName = time().'_'.$request->pdf->getClientOriginalName();
            $filePath = $request->file('pdf')->storeAs('ordinances', $fileName, 'public');
    
            $ordinance->pdf_name = $fileName;
            $ordinance->file_path = $filePath;
    
            $ordinance->save();
    
            return response()->json([
                'success' => true,
                'message' => 'New ordinance created succesfully',
                'ordinance' => new OrdinanceResource($ordinance->load('ordinance_category'))
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
        //
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
            $ordinance = Ordinance::with('ordinance_category')->findOrFail($id);

            // load all ordinance_category to select the user
            $ordinance_categories = OrdinanceCategory::get();

            return response()->json([
                'success' => true,
                'message' => 'Found ordinance data',
                'ordinance' => new OrdinanceResource($ordinance),
                'ordinance_categories' => OrdinanceCategoryResource::collection($ordinance_categories)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No ordinance id found',
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
            'ordinance_no'=> 'required|integer',
            'title'=> 'required|string|min:1|max:60',
            'date_approved' => 'required',
            'ordinance_category_id' => 'required|integer|exists:ordinance_categories,id',
            'pdf' => 'required|mimes:pdf|max:10000'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        try {
            $ordinance = Ordinance::with('ordinance_category')->findOrFail($id);
            $ordinance->ordinance_no = $request->ordinance_no;
            $ordinance->title = $request->title;
            $ordinance->date_approved = $request->date_approved;
            $ordinance->ordinance_category_id = $request->ordinance_category_id;
            //check if they want to update the pdf file
            if($request->hasFile('pdf')) {
                Storage::delete('public/ordinances/'. $ordinance->pdf_name);

                $fileName = time().'_'.$request->pdf->getClientOriginalName();
                $filePath = $request->file('pdf')->storeAs('ordinances', $fileName, 'public');

                $ordinance->pdf_name = $fileName;
                $ordinance->file_path = $filePath;
            }

            $ordinance->save();

            return response()->json([
                'success' => true,
                'message' => 'The ordinance is successfully updated',
                'ordinance' => new OrdinanceResource($ordinance)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No ordinance id found',
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
            $ordinance = Ordinance::findOrFail($id);
            Storage::delete('public/ordinances/'. $ordinance->pdf_name);
            $ordinance->delete();
            return response()->json([
                'success' => true,
                'message' => 'The ordinance is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No ordinance id found',
            ]);
        }
    }
}
