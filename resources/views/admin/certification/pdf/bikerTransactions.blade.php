@extends('layouts.newReport')

@section('title', $title)

@section('content')
    <div class = "rep">
        <br>
        <br>
        <b> {{ $title }} </b>
        </h6>
        <br>
        <b>Generated By:</b><i>: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (#{{ Auth::id() }})</i>
        <br>
        <b>Position:</b> <i>{{ Auth::user()->user_role->role }} </i>
        <br>
        <b>Date Generated:</b><i>{{ date('Y-m-d H:i:s') }}</i>
    </div>


    <hr style="width:45%; margin-left: 0.1rem; background-color:gray; height: 2px;" >
    <div class = "timef">
        <h6>
        <b>Timeframe</b>
    </div>

    <div class ="dits">
        <div class="row p-4">
            <div class="column" style="font-size: 18px">
                <p class="font-weight-bold">Date Start:</p>
                <p class="font-weight-bold">Date End:</p>
                <p class="font-weight-bold">Sort by:</p>
                <p class="font-weight-bold">Order Status filter:</p>
            </div>
            <div class="column" style="font-size: 18px">
                <p class="text-right"> {{$date_start}} </p>
                <p class="text-right"> {{ $date_end  }} </p>
                <p class="text-right">  {{ str_replace(array("types."),"",$sort_column ); }} ({{{ $sort_option }}}) </p>
                <p class="text-right"> {{ $order_status  }} </p>
            </div>
            <hr style="width:100%; background-color:gray; height: 2px;" >
        </div>
    </div>

    <div class="container mb-3">
        <h4 class="text-center">Overall Statistics</h4>
        <hr/>
        <div class="row">
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm-6">
                        Bike Type:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $user->bike_type }}</div>
                    </div>
                    <div class="col-sm-6">
                        Bike Color:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $user->bike_color }}</div>
                    </div>
                    <div class="col-sm-6">
                        Bike Size:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $user->bike_size }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="row">
                    <div class="col-sm-6">
                        Received:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $reportsData->received_count }}</div>
                    </div>

                    <div class="col-sm-6">
                        DNR:
                    </div>
                    <div class="col-sm-6">
                        <div class="font-weight-bold">{{ $reportsData->dnr_count }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="regiz">
        <div class="regi">
            <pre> <b>{{ $modelName }} count:</b> {{ $user->deliverySuccess->count() }}</pre>
            <pre> <b>Earnings:</b> {{ '₱ '.($user->deliverySuccess->sum('delivery_fee')) }}</pre>
        </div>
    </div>

    <table class="tableContent mb-4">
        <thead style="background: #F5F5F5;">
            <tr>
                <th>ID</th>
                <th>Ordered by</th>
                <th>Location</th>
                <th>Total Price</th>
                <th>Delivery Fee</th>
                <th>Pickup Date</th>
                <th>Delivered Date</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td scope="row">{{ $order->id }}</td>

                    <td>
                        @if ($order->ordered_by != null)
                            (#{{$order->ordered_by}}){{ $order->contact->getFullNameAttribute() }}
                        @else
                            {{ $order->name }}
                        @endif
                    </td>
                    <td>{{ $order->location_address }}</td>
                    <td>{{ '₱'.$order->total_price }}</td>
                    <td>{{ '₱'.$order->delivery_fee }}</td>

                    <td>{{ \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y') }}</td>

                    @if ($order->received_at != null)
                            <td>{{ \Carbon\Carbon::parse($order->received_at)->format('F d, Y') }}</td>
                        @else
                            <td>Not Delivered</td>
                        @endif
                    <td>
                        <p>Application:<br>
                            <strong> {{ $order->application_status }} </strong>
                        </p>

                        <p>Pick up:<br>
                            <strong> {{ $order->pick_up_type }} </strong>
                        </p>

                        <p>Order:<br>
                            <strong> {{ $order->order_status }} </strong>
                        </p>
                    </td>
                    <td>{{ $order->created_at->format(' jS \\of F Y ') }}</td>

                </tr>
            @endforeach

        </tbody>
    </table>
@endsection