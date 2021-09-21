<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrdinanceRequest;
use App\Http\Resources\OrdinanceResource;
use App\Http\Resources\TypeResource;
use App\Models\Ordinance;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class OrdinanceController extends Controller
{
    public function index()
    {
        $ordinances = Ordinance::with('type')->orderBy('created_at','DESC')->get();
        return OrdinanceResource::collection($ordinances)->additional(['success' => true]);
    }

    public function create()
    {
        $types = Type::where('model_type', 'Ordinance')->get();
        return ['types' => TypeResource::collection($types), 'success' => true];
    }

    public function store(OrdinanceRequest $request)
    {
        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('ordinances', $fileName, 'public');
        $ordinance = Ordinance::create(array_merge($request->getData(), ['pdf_name' => $fileName,'file_path' => $filePath]));
        return (new OrdinanceResource($ordinance->load('type')))->additional(Helper::instance()->storeSuccess('ordinance'));
    }

    public function show(Ordinance $ordinance)
    {
        return (new OrdinanceResource($ordinance->load('type')))->additional(Helper::instance()->itemFound('ordinance'));
    }

    public function edit(Ordinance $ordinance)
    {
        $types = Type::where('model_type', 'Ordinance')->get();
        return (new OrdinanceResource($ordinance->load('type')))->additional(array_merge(['types' => TypeResource::collection($types)],Helper::instance()->itemFound('ordinance')));
    }

    public function update(OrdinanceRequest $request, Ordinance $ordinance)
    {
        if($request->hasFile('pdf')) {
            Storage::delete('public/ordinances/'. $ordinance->pdf_name);
            $fileName = time().'_'.$request->pdf->getClientOriginalName();
            $filePath = $request->file('pdf')->storeAs('ordinances', $fileName, 'public');
            $ordinance->fill(array_merge($request->getData(), ['custom_type' => NULL,'pdf_name' => $fileName,'file_path' => $filePath]))->save();
        } else { $ordinance->fill(array_merge($request->getData(), ['custom_type' => NULL]))->save(); }
        return (new OrdinanceResource($ordinance->load('type')))->additional(Helper::instance()->updateSuccess('ordinance'));
    }

    public function destroy(Ordinance $ordinance)
    {
        Storage::delete('public/ordinances/'. $ordinance->pdf_name);
        $ordinance->delete();
        return response()->json(Helper::instance()->destroySuccess('ordinance'));
    }
}
