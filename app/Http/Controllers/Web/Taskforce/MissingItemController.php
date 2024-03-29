<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\MissingItemRequest;
use App\Http\Resources\MissingItemResource;
use App\Jobs\ChangeStatusReportJob;
use App\Jobs\SendSingleNotificationJob;
use App\Models\MissingItem;
use Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DB;
use Helper;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;
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
            $fileName = uniqid().'-'.time();
            $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
            $lost_and_found = MissingItem::create(array_merge($request->getData(), ['user_id' => Auth::id(), 'status' => 'Approved', 'picture_name' => $result->getPublicId(), 'file_path' => $result->getPath()]));
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
                Cloudinary::destroy($missing_item->picture_name);
                $fileName = uniqid().'-'.time();
                $result = $request->file('picture')->storeOnCloudinaryAs(env('CLOUDINARY_PATH', 'dev-barangay'), $fileName);
                $missing_item->fill(array_merge($request->getData(), ['picture_name' => $result->getPublicId(), 'file_path' => $result->getPath()]))->save();
            } else { $missing_item->fill(array_merge($request->getData()))->save(); }
            return (new MissingItemResource($missing_item->load('comments', 'contact')->loadCount('comments')))->additional(Helper::instance()->updateSuccess('missing-item report'));
        }
    }

    public function destroy(MissingItem $missing_item)
    {
        if(request()->ajax()) {
            Cloudinary::destroy($missing_item->picture_name);
            if ($missing_item->credential_name != Null) {
                Cloudinary::destroy($missing_item->credential_name);
            }
            $missing_item->comments()->delete();
            $missing_item->delete();
            return response()->json(Helper::instance()->destroySuccess('missing-item report'));
        }
    }

    public function changeStatus(ChangeStatusRequest $request, MissingItem $missing_item) {
        $oldStatus = $missing_item->status;
        $missing_item->fill($request->validated())->save();

        $subject = 'Missing Item Report Change Status Notification';
        $reportName = 'missing item report';

        dispatch(
            new SendSingleNotificationJob(
                $missing_item->contact->device_id, $missing_item->contact->id, "Missing Item Report Change Status Notification",
                "Your submitted missing item #".$missing_item->id." status has been change by the administrator.", $missing_item->id,  "App\Models\MissingItem"
        ));

        dispatch(new ChangeStatusReportJob($missing_item->user->email, $missing_item->id, $reportName, $missing_item->status, $missing_item->admin_message, $subject, $missing_item->phone_no));

        return (new MissingItemResource($missing_item))->additional(Helper::instance()->statusMessage($oldStatus, $missing_item->status, 'missing-item'));
    }

    public function report($date_start,  $date_end, $sort_column, $sort_option, $report_option, $status_option) {

        try {
            $missingItems = MissingItem::whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($report_option, $status_option) {
                    if($report_option == 'all' && $status_option == 'all') {
                        return null;
                    } elseif ($report_option == 'all' && $status_option != 'all') {
                        return $query->where('status', '=', ucwords($status_option));
                    } elseif ($report_option != 'all' && $status_option == 'all') {
                        return $query->where('report_type', '=', ucwords($report_option));
                    } else {
                        return $query->where('status', '=', ucwords($status_option))
                        ->where('report_type', '=', ucwords($report_option));
                    }
                })->get();
        } catch(\Illuminate\Database\QueryException $ex){}

        if ($missingItems->isEmpty()) {
            $title = 'Report - No data';
            $description = 'No data';
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('reports')
            ->selectRaw('count(*) as missing_items_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Resolved' then 1 end) as resolved_count")
            ->selectRaw("count(case when status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when status = 'Approved' then 1 end) as approved_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($report_option, $status_option) {
                if($report_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($report_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($report_option != 'all' && $status_option == 'all') {
                    return $query->where('report_type', '=', ucwords($report_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('report_type', '=', ucwords($report_option));
                }
            })->first();

        $title = 'Missing Item Reports';
        $modelName = 'Missing Item';

        return view('admin.taskforce.pdf.missingItem', compact('title', 'modelName', 'missingItems', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'report_option', 'status_option'
        ));
    }

}
