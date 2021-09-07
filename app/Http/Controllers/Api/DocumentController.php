<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use App\Models\DocumentType;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentTypeResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::with('document_type')->orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'documents' => DocumentResource::collection($documents)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // load all document_type to select the user
        $document_types = DocumentType::get();

        return response()->json([
            'success' => true,
            'document_types' => DocumentTypeResource::collection($document_types)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        $document = new Document();
        $document->document_type_id = $request->document_type_id;
        $document->description = $request->description;
        $document->year = $request->year;

        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');

        $document->pdf_name = $fileName;
        $document->file_path = $filePath;

        $document->save();

        return response()->json([
            'success' => true,
            'message' => 'New document created succesfully',
            'document' => new DocumentResource($document->load('document_type'))
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
            $document = Document::with('document_type')->findOrFail($id);

            // load all document_type to select the user
            $document_types = DocumentType::get();

            return response()->json([
                'success' => true,
                'message' => 'Found document data',
                'document' => new DocumentResource($document),
                'document_types' => DocumentTypeResource::collection($document_types)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('document'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentRequest $request, $id)
    {
        try {
            $document = Document::with('document_type')->findOrFail($id);
            $document->document_type_id = $request->document_type_id;
            $document->description = $request->description;
            $document->year = $request->year;

            //check if they want to update the pdf file
            if($request->hasFile('pdf')) {
                Storage::delete('public/documents/'. $document->pdf_name);

                $fileName = time().'_'.$request->pdf->getClientOriginalName();
                $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');

                $document->pdf_name = $fileName;
                $document->file_path = $filePath;
            }

            $document->save();

            return response()->json([
                'success' => true,
                'message' => 'The document is successfully updated',
                'document' => new DocumentResource($document)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('document'));
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
            $document = Document::findOrFail($id);
            Storage::delete('public/documents/'. $document->pdf_name);
            $document->delete();
            return response()->json([
                'success' => true,
                'message' => 'The document is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('document'));
        }
    }
}
