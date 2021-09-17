<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementTypeRequest;
use App\Http\Resources\AnnouncementTypeResource;
use App\Models\Announcement;
use App\Models\AnnouncementType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AnnouncementTypeController extends Controller
{

    public function index()
    {
        $announcement_types = AnnouncementType::withCount('announcements')->orderBy('created_at','DESC')->get();
        return response()->json([
            'success' => true,
            'announcement_types' => AnnouncementTypeResource::collection($announcement_types)
        ]);
    }

    public function create()
    {
        //
    }

    public function store(AnnouncementTypeRequest $request)
    {
        $announcement_type = new AnnouncementType();
        $announcement_type->type = $request->type;
        $announcement_type->save();
        $announcement_type->announcement_type = 0;

        return response()->json([
            'success' => true,
            'message' => 'New announcement type created succesfully',
            'announcement_type' => new AnnouncementTypeResource($announcement_type)
        ]);
    }

    public function show($id)
    {
        try {
            $announcement_type = AnnouncementType::with('announcements')->withCount('announcements')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found announcement type data',
                'announcement_type' => new AnnouncementTypeResource($announcement_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('announcement type'));
        }
    }

    public function edit($id)
    {
        try {
            $announcement_type = AnnouncementType::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found announcement type data',
                'announcement_type' => new AnnouncementTypeResource($announcement_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('announcement type'));
        }
    }

    public function update(AnnouncementTypeRequest $request, $id)
    {
        try {
            $announcement_type = AnnouncementType::withCount('announcements')->orderBy('created_at','DESC')->findOrFail($id);
            $announcement_type->type = $request->type;
            $announcement_type->save();

            return response()->json([
                'success' => true,
                'message' => 'The announcement type is successfully updated',
                'announcement_type' => new AnnouncementTypeResource($announcement_type)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('announcement type'));
        }
    }


    public function destroy($id)
    {
        try {
            $announcement_type = AnnouncementType::findOrFail($id);
            $announcement_type->delete();
            return response()->json([
                'success' => true,
                'message' => 'The announcement type type is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('announcement type type'));
        }
    }
}
