<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Http\Resources\DocumentTypeResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $document_types = DocumentType::withCount('documents')->orderBy('created_at','DESC')->get();
        return response()->json([
            'success' => true,
            'document_types' => DocumentTypeResource::collection($document_types)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'type' => 'required|unique:document_types|string|min:1|max:120',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        $document_type = new DocumentType();
        $document_type->type = $request->type;
        $document_type->save();
        $document_type->documents_count = 0;

        return response()->json([
            'success' => true,
            'message' => 'New document type created succesfully',
            'document_type' => new DocumentTypeResource($document_type)
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
            $document_type = DocumentType::with('documents')->withCount('documents')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found document type data',
                'document_type' => new DocumentTypeResource($document_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No document type found',
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
            $document_type = DocumentType::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found document type data',
                'document_type' => new DocumentTypeResource($document_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No document type id found',
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
            'type' => 'required|unique:document_types|string|min:1|max:120'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error->errors(),
            ]);
        }

        try {
            $document_type = DocumentType::withCount('documents')->findOrFail($id);
            $document_type->type = $request->type;
            $document_type->save();

            return response()->json([
                'success' => true,
                'message' => 'The document type is successfully updated',
                'document_type' => new DocumentTypeResource($document_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No document type id found',
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
            $type = DocumentType::findOrFail($id);
            $type->delete();
            return response()->json([
                'success' => true,
                'message' => 'The document type is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No document type id found',
            ]);
        }
    }
}
