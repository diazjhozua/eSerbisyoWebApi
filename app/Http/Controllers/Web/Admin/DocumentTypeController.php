<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentTypeRequest;
use App\Http\Requests\Report\DocumentReportRequest;
use App\Http\Requests\Report\TypeReportRequest;
use App\Http\Resources\TypeResource;
use App\Models\Document;
use App\Models\Type;
use Barryvdh\DomPDF\Facade as PDF;


class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('documents')->where('model_type', 'Document')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Document w/o document type)', 'model_type' => 'Document', 'created_at' => now(), 'updated_at' => now(),
            'documents_count' => Document::where('type_id', NULL)->count() ]));

        return view('admin.information.document-types.index')->with('types', $types);
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

        return view('admin.information.document-types.show')->with('type', $type);
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

    public function report($date_start, $date_end, $sort_column, $sort_option) {

        $title = 'Report - No data';
        $description = 'No data';
        try {

        $types = Type::withCount('documents as count')
        ->where('model_type', 'Document')->orderBy('created_at','DESC')
        ->whereBetween('created_at', [$date_start, $date_end])
        ->orderBy($sort_column, $sort_option)
        ->get();


        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Document w/o document type)', 'model_type' => 'Document', 'created_at' => now(), 'updated_at' => now(),
            'count' => Document::where('type_id', NULL)->count() ]));

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }
        if ($types->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }


        $title = 'Document Type Publish Report';
        $modelName = 'Document';


        return view('admin.information.pdf.documentTypes', compact('title', 'modelName', 'types',
        'date_start', 'date_end', 'sort_column', 'sort_option'
    ));
    }

    public function reportShow($date_start, $date_end, $sort_column, $sort_option, $type_id) {

        $title = 'Report - No data';
        $description = 'No data';

        try {

            $documents = Document::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->where(function($query) use ($type_id) {
                if ($type_id == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $type_id);
                }
            })
            ->get();

            } catch(\Illuminate\Database\QueryException $ex){
                return view('errors.404Report', compact('title', 'description'));
            }
            if ($documents->isEmpty()) {
                return view('errors.404Report', compact('title', 'description'));
            }


        $type = Type::find($type_id);
        $title = 'Document Type Reports';
        $modelName =  $type_id == 0 ? 'Others/Deleted' : $type->name;

        return view('admin.information.pdf.documents', compact('title', 'modelName','documents',
        'date_start', 'date_end', 'sort_column', 'sort_option'
    ));
    }

}
