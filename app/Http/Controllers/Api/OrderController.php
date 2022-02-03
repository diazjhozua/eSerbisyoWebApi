<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function certificates()
    {
        // list of certicates
        $certificates = Certificate::with('requirements')->withCount('requirements')->get();
        return CertificateResource::collection($certificates);
    }

    public function index()
    {
        // authenticated user orders list
        $orders = Order::where('ordered_by', auth('api')->user()->id)->where('pick_up_type', '!=', 'Walkin')->orderBy('created_at','DESC')->get();
        return response()->json(["data" => $orders], 200);
    }

    public function create($pickupType)
    {
        // get available certificate depending on the pickup type
        if ( $pickupType == "Delivery") {
            $certificates = Certificate::where('status', 'Available')->where('is_open_delivery', '=', 1)->get();
        } else {
            $certificates = Certificate::where('status', 'Available')->get();
        }

        return response()->json(["data" => $certificates], 200);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
