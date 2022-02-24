<?php

namespace App\Http\Controllers\WEb\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\TypeResource;
use App\Models\Document;
use App\Models\Type;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('last day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $documentsData =  DB::table('documents')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->first();

        $documents = Document::with('type')->orderBy('created_at','DESC')->get();
        return view('admin.information.documents.index', compact('documentsData', 'documents'));
    }

    public function create()
    {
        $types = Type::where('model_type', 'Document')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(DocumentRequest $request)
    {

        $fileName = uniqid().'-'.time();
        $result = $request->file('pdf')->storeOnCloudinaryAs('barangay', $fileName);
        $document = Document::create(array_merge($request->getData(), ['pdf_name' => $result->getPublicId(), 'file_path' => $result->getPath()]));
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
            Cloudinary::destroy($document->pdf_name);
            $fileName = uniqid().'-'.time();
            $result = $request->file('pdf')->storeOnCloudinaryAs('barangay', $fileName);
            $document->fill(array_merge($request->getData(), ['custom_type' => NULL, 'pdf_name' => $result->getPublicId(), 'file_path' => $result->getPath()]))->save();
        } else {
            $document->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save();
        }
        return (new DocumentResource($document->load('type')))->additional(Helper::instance()->updateSuccess('document'));
    }

    public function destroy(Document $document)
    {
        Cloudinary::destroy($document->pdf_name);
        $document->delete();
        return response()->json(Helper::instance()->destroySuccess('document'));
    }

    public function report($date_start, $date_end, $sort_column, $sort_option) {
        $title = 'Report - No data';
        $description = 'No data';

        try {

            $documents = Document::with('type')
            ->whereBetween('created_at', [$date_start, $date_end])
            ->orderBy($sort_column, $sort_option)
            ->get();



            } catch(\Illuminate\Database\QueryException $ex){
                return view('errors.404Report', compact('title', 'description'));
            }



        if ($documents->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $documentsData = null;

        $firstDayYear = date('Y-m-d', strtotime('first day of january this year'));
        $lastDateYear = date('Y-m-d', strtotime('last day of december this year'));
        $firstDayMonth = date('Y-m-d',strtotime('first day of this month'));
        $lastDayMonth = date('Y-m-d',strtotime('last day of this month'));

        $documentsData =  DB::table('documents')
        ->selectRaw("count(case when created_at >='". $firstDayYear ."' AND created_at <='".$lastDateYear."' then 1 end) as this_year_count")
        ->selectRaw("count(case when created_at >='". $firstDayMonth ."' AND created_at <='".$lastDayMonth."' then 1 end) as this_month_count")
        ->selectRaw("count(case when DATE(created_at) = CURDATE() then 1 end) as this_day_count")
        ->first();


        $title = 'Document Publish Report';
        $modelName = 'Document';

        // $pdf = PDF::loadView('admin.information.reports.document', compact('documents', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        // return $pdf->stream();
        return view('admin.information.pdf.documentreport', compact('title', 'modelName', 'documents' ,'documentsData',
        'date_start', 'date_end', 'sort_column', 'sort_option'
    ));
    }

}
