<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // list of users sort by with highest number of orders (DESC)
    public function index() {
        $users = User::withCount('orders')->orderBy('orders_count', 'DESC')->get();

        return view('admin.certification.transactions.index', compact('users'));
    }

    // show specific user transactions made
    public function show($id) {
        $user = User::findOrFail($id);
        $orders = Order::withCount('certificateForms')->where('ordered_by', '=', $user->id)->get();
        $receivedOrders = Order::where('order_status', 'Received')->where('ordered_by', '=', $user->id)->get();
        return view('admin.certification.transactions.show', compact('orders', 'user', 'receivedOrders'));
    }

    public function report($user_id, $date_start,  $date_end, $sort_column, $sort_option, $pick_up_type, $order_status, $application_status) {
        $title = 'Report - No data';
        $description = 'No data';

        $user = User::with('orderSuccess')->findOrFail($user_id);
        try {
            $orders = Order::withCount('certificateForms')->whereBetween('created_at', [$date_start, $date_end])
                ->where('ordered_by', '=', $user_id)
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($pick_up_type) {
                    if($pick_up_type == 'all') {
                        return null;
                    } else {
                        return $query->where('pick_up_type', '=', $pick_up_type);
                    }
                })
                ->where(function($query) use ($order_status) {
                    if($order_status == 'all') {
                        return null;
                    } else {
                        return $query->where('order_status', '=', $order_status);
                    }
                })
                ->where(function($query) use ($application_status) {
                    if($application_status == 'all') {
                        return null;
                    } else {
                        return $query->where('application_status', '=', $application_status);
                    }
                })
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($orders->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        $reportsData = null;

        $reportsData =  DB::table('orders')
            ->selectRaw('count(*) as orders_count')
            ->selectRaw('sum(total_price) as total_price')
            ->selectRaw('sum(delivery_fee) as delivery_fee')
            ->selectRaw("count(case when application_status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when application_status = 'Cancelled' then 1 end) as cancelled_count")
            ->selectRaw("count(case when application_status = 'Approved' then 1 end) as approved_count")
            ->selectRaw("count(case when application_status = 'Denied' then 1 end) as denied_count")
            ->selectRaw("count(case when pick_up_type = 'Walkin' then 1 end) as walkin_count")
            ->selectRaw("count(case when pick_up_type = 'Pickup' then 1 end) as pickup_count")
            ->selectRaw("count(case when pick_up_type = 'Delivery' then 1 end) as delivery_count")
            ->selectRaw("count(case when order_status = 'Waiting' then 1 end) as waiting_count")
            ->selectRaw("count(case when order_status = 'Received' then 1 end) as received_count")
            ->selectRaw("count(case when order_status = 'DNR' then 1 end) as dnr_count")
            ->where('ordered_by', '=', $user_id)
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($pick_up_type) {
                if($pick_up_type == 'all') {
                    return null;
                } else {
                    return $query->where('pick_up_type', '=', $pick_up_type);
                }
            })
            ->where(function($query) use ($order_status) {
                if($order_status == 'all') {
                    return null;
                } else {
                    return $query->where('order_status', '=', $order_status);
                }
            })
            ->where(function($query) use ($application_status) {
                if($application_status == 'all') {
                    return null;
                } else {
                    return $query->where('application_status', '=', $application_status);
                }
            })
            ->first();

        $title = $user->getFullNameAttribute().' #'.$user->id.' User Transaction Reports';
        $modelName = 'User Transaction History';
        return view('admin.certification.pdf.userTransactions', compact('title', 'modelName', 'user', 'orders', 'reportsData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'pick_up_type', 'order_status', 'application_status',
        ));
    }
}
