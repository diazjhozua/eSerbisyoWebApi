<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\TypeResource;
use App\Models\Document;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('type')->orderBy('created_at','DESC')->get();
        return DocumentResource::collection($documents)->additional(['success' => true]);
    }

    // public function create()
    // {
    //     $types = Type::where('model_type', 'Document')->get();
    //     return ['types' => TypeResource::collection($types), 'success' => true];
    // }

    // public function store(DocumentRequest $request)
    // {
    //     $fileName = time().'_'.$request->pdf->getClientOriginalName();
    //     $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');
    //     $document = Document::create(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]));
    //     return (new DocumentResource($document->load('type')))->additional(Helper::instance()->storeSuccess('document'));
    // }

    public function show(Document $document)
    {
        return (new DocumentResource($document->load('type')))->additional(Helper::instance()->itemFound('document'));
    }

    // public function edit(Document $document)
    // {
    //     $types = Type::where('model_type', 'Document')->get();
    //     return (new DocumentResource($document->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('document')));
    // }

    // public function update(DocumentRequest $request, Document $document)
    // {
    //     if($request->hasFile('pdf')) {
    //         Storage::delete('public/documents/'. $document->pdf_name);
    //         $fileName = time().'_'.$request->pdf->getClientOriginalName();
    //         $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');
    //         $document->fill(array_merge($request->getData(), ['custom_type' => NULL,'pdf_name' => $fileName,'file_path' => $filePath]))->save();
    //     } else { $document->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
    //     return (new DocumentResource($document->load('type')))->additional(Helper::instance()->updateSuccess('document'));
    // }

    // public function destroy(Document $document)
    // {
    //     Storage::delete('public/documents/'. $document->pdf_name);
    //     $document->delete();
    //     return response()->json(Helper::instance()->destroySuccess('document'));
    // }
}
