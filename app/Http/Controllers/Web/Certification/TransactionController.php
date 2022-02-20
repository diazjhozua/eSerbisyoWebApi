<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
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
}
