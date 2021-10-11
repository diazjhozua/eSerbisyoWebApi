<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementTypeRequest;
use App\Http\Resources\TypeResource;
use App\Models\Announcement;
use App\Models\Type;

class AnnouncementTypeController extends Controller
{

    public function index()
    {
        $types = Type::withCount('announcements')->where('model_type', 'Announcement')->orderBy('created_at','DESC')->get();
        $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Report', 'created_at' => now(), 'updated_at' => now(),
            'announcements_count' => Announcement::where('type_id', NULL)->count() ]));
        return TypeResource::collection($types)->additional(['success' => true]);
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
            $announcements = Announcement::where('type_id', NULL)->orderBy('created_at', 'DESC')->get();
            $type = (new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Announcement', 'created_at' => now(), 'updated_at' => now(),
            'announcements_count' => $announcements->count(), 'others' => $announcements ]));
        } else {
            $type = Type::with(['announcements' => function($query) {
                $query->with('announcement_pictures')->withCount('comments');
            }])->where('model_type', 'Announcement')->withCount('announcements')->findOrFail($id); }
        return (new TypeResource($type))->additional(Helper::instance()->itemFound('announcement_type'));
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
}
