<?php

namespace App\Http\Controllers\WEb\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\TypeResource;
use App\Models\Document;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('first day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $documentsData =  DB::table('documents')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->first();

        $documents = Document::with('type')->orderBy('created_at','DESC')->get();
        return view('admin.documents.index', compact('documentsData', 'documents'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Document')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(DocumentRequest $request)
    {
        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');
        $document = Document::create(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]));
        return (new DocumentResource($document->load('type')))->additional(Helper::instance()->storeSuccess('document'));
    }

    public function edit(Document $document)
    {
        $types = Type::where('model_type', 'Document')->get();
        return (new DocumentResource($document->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('document')));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        if($request->hasFile('pdf')) {
            Storage::delete('public/documents/'. $document->pdf_name);
            $fileName = time().'_'.$request->pdf->getClientOriginalName();
            $filePath = $request->file('pdf')->storeAs('documents', $fileName, 'public');
            $document->fill(array_merge($request->getData(), ['custom_type' => NULL,'pdf_name' => $fileName,'file_path' => $filePath]))->save();
        } else { $document->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
        return (new DocumentResource($document->load('type')))->additional(Helper::instance()->updateSuccess('document'));
    }

    public function destroy(Document $document)
    {
        Storage::delete('public/documents/'. $document->pdf_name);
        $document->delete();
        return response()->json(Helper::instance()->destroySuccess('document'));
    }

}
