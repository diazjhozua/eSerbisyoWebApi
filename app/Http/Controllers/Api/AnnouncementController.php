<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{

    public function index()
    {
        $announcements = Announcement::withCount('comments')->with('announcement_pictures', 'comments')->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'announcements' => AnnouncementResource::collection($announcements)
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
