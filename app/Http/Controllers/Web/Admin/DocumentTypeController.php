<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Document;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('documents')->where('model_type', 'Document')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Document w/o document type)', 'model_type' => 'Document', 'created_at' => now(), 'updated_at' => now(),
            'documents_count' => Document::where('type_id', NULL)->count() ]));

        return view('admin.document-types.index')->with('types', $types);
    }

    public function store(DocumentTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Document']));
        $type->documents_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('document_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $documents = Document::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Document w/o document type', 'model_type' => 'Document', 'created_at' => now(), 'updated_at' => now(),
            'documents_count' => $documents->count(), 'documents' => $documents ]));
        } else {  $type = Type::with('documents')->where('model_type', 'Document')->withCount('documents')->findOrFail($id); }

        return view('admin.document-types.show')->with('type', $type);
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Document')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('document_type'));
    }

    public function update(DocumentTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('documents')->where('model_type', 'Document')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('document_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Document')->findOrFail($id);
        Document::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('document_type'));
    }
}
