<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\MissingItemRequest;
use App\Http\Resources\MissingItemResource;
use App\Jobs\ChangeStatusReportJob;
use App\Models\MissingItem;
use Auth;
use DB;
use Helper;
use Illuminate\Http\Request;
use Storage;

class MissingItemController extends Controller
{

    public function index()
    {
        $missingItemsData =  DB::table('missing_items')
        ->selectRaw('count(*) as missing_persons_count')
        ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
        ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
        ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
        ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
        ->where('created_at', '>=', date('Y-m-d',strtotime('first day of this month')))
        ->where('created_at', '<=', date('Y-m-d',strtotime('last day of this month')))
        ->first();

        $missing_items = MissingItem::withCount('comments')->orderBy('created_at','DESC')->get();

        return view('admin.taskforce.missing-items.index', compact('missing_items', 'missingItemsData'));
    }

    public function create()
    {
        if(request()->ajax()) {
            $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
            return response()->json(['reportTypes' => $reportTypes, 'success' => true]);
        }
    }


    public function store(MissingItemRequest $request)
    {
        if(request()->ajax()) {
            $fileName = time().'_'.$request->picture->getClientOriginalName();
            $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
            $lost_and_found = MissingItem::create(array_merge($request->getData(), ['user_id' => Auth::id(), 'status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]));
            return (new MissingItemResource($lost_and_found))->additional(Helper::instance()->storeSuccess('missing-item report'));
        }
    }

    public function show(MissingItem $missing_item)
    {
        $missing_item->load('comments')->loadCount('comments');
        return view('admin.taskforce.missing-items.show', compact('missing_item'));
    }

    public function edit(MissingItem $missing_item)
    {
        if(request()->ajax()) {
            $reportTypes = [ (object)[ "id" => 1, "type" => "Missing"],(object) ["id" => 2,"type" => "Found"] ];
            return (new MissingItemResource($missing_item))->additional(array_merge(['reportTypes' => $reportTypes], Helper::instance()->itemFound('missing-item report')));
        }
    }

    public function update(MissingItemRequest $request, MissingItem $missing_item)
    {
        if(request()->ajax()) {
            if($request->hasFile('picture')) {
                Storage::delete('public/missing-pictures/'. $missing_item->picture_name);
                $fileName = time().'_'.$request->picture->getClientOriginalName();
                $filePath = $request->file('picture')->storeAs('missing-pictures', $fileName, 'public');
                $missing_item->fill(array_merge($request->getData(), ['status' => 'Pending', 'picture_name' => $fileName,'file_path' => $filePath]))->save();
            } else { $missing_item->fill(array_merge($request->getData(), ['status' => 'Pending']))->save(); }
            return (new MissingItemResource($missing_item->load('comments')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing-item report'));
        }
    }

    public function destroy(MissingItem $missing_item)
    {
        if(request()->ajax()) {
            Storage::delete('public/missing-pictures/'. $missing_item->picture_name);
            $missing_item->comments()->delete();
            $missing_item->delete();
            return response()->json(Helper::instance()->destroySuccess('missing-item report'));
        }
    }

    public function changeStatus(ChangeStatusRequest $request, MissingItem $missing_item) {
        // if ($request->status == $lost_and_found->status) {
        //     return response()->json(Helper::instance()->sameStatusMessage($request->status, 'lost-and-found report'));
        // }

        $oldStatus = $missing_item->status;
        $missing_item->fill($request->validated())->save();

        $subject = 'Missing Item Report Change Status Notification';
        $reportName = 'missing item report';
        dispatch(new ChangeStatusReportJob($missing_item->user->email, $missing_item->id, $reportName, $missing_item->status, $missing_item->admin_message, $subject));

        return (new MissingItemResource($missing_item))->additional(Helper::instance()->statusMessage($oldStatus, $missing_item->status, 'missing-item'));
    }
}
