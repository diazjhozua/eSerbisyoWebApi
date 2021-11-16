<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementTypeRequest;
use App\Http\Requests\Report\AnnouncementReportRequest;
use App\Http\Requests\Report\TypeReportRequest;
use App\Http\Resources\TypeResource;
use App\Models\Announcement;
use App\Models\Type;
use Barryvdh\DomPDF\Facade as PDF;

class AnnouncementTypeController extends Controller
{

    public function index()
    {
        $types = Type::withCount('announcements')->where('model_type', 'Announcement')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Announcement with deleted types)   ', 'model_type' => 'Announcement', 'created_at' => now(), 'updated_at' => now(),
            'announcements_count' => Announcement::where('type_id', NULL)->count() ]));

        return view('admin.information.announcement-types.index')->with('types', $types);
    }

    public function store(AnnouncementTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Announcement']));
        $type->announcements_count = 0;
        return (new TypeResource($type))->additional(Helper::instance()->storeSuccess('announcement_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $announcements = Announcement::with('type')->withCount('comments', 'likes', 'announcement_pictures')->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Announcement', 'created_at' => now(), 'updated_at' => now(),
            'announcements_count' => $announcements->count(), 'others' => $announcements ]));
        } else {
            $type = Type::with(['announcements' => function($query) {
                $query->withCount('comments', 'likes', 'announcement_pictures');
            }])->where('model_type', 'Announcement')->withCount('announcements')->findOrFail($id); }

        return view('admin.information.announcement-types.show')->with('type', $type);
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Announcement')->findOrFail($id);
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('announcement_type'));
    }

    public function update(AnnouncementTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('announcements')->where('model_type', 'Announcement')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new TypeResource($type))->additional(Helper::instance()->updateSuccess('announcement_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Announcement')->findOrFail($id);
        Announcement::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('announcement_type'));
    }

    public function report(TypeReportRequest $request) {
        $types = Type::withCount('announcements as count')
            ->where('model_type', 'Announcement')->orderBy('created_at','DESC')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->get();

        if ($types->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $types->add(new Type([ 'id' => 0, 'name' => 'Others (Announcement w/o announcement type)', 'model_type' => 'Announcement', 'created_at' => now(), 'updated_at' => now(),
            'count' => Announcement::where('type_id', NULL)->count() ]));

        $title = 'Announcement Type Publish Report';
        $modelName = 'Announcement';

        $pdf = PDF::loadView('admin.information.reports.type', compact('types', 'request', 'title', 'modelName'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function reportShow(AnnouncementReportRequest $request, $typeID) {
        $announcements = Announcement::with('type')
            ->withCount('comments', 'likes', 'announcement_pictures')
            ->whereBetween('created_at', [$request->date_start, $request->date_end])
            ->orderBy($request->sort_column, $request->sort_option)
            ->where(function($query) use ($typeID) {
                if ($typeID == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $typeID);
                }
            })
            ->get();

        if ($announcements->isEmpty()) {
            return response()->json(['No data'], 404);
        }

        $pdf = PDF::loadView('admin.information.reports.announcement', compact('announcements', 'request'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}

