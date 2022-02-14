@extends('layouts.admin')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/certification/orders/index.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Certificate Orders')


@section('content')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')
    @include('admin.certification.orders.reportSelectModal')
    @include('admin.certification.orders.modals.pendingOrder')
    @include('admin.certification.orders.modals.returnableOrder')
    @include('admin.certification.orders.modals.unprocessedOrder')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Orders
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>

        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <!-- Button trigger modal -->
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary" target="_blank">
        Record
    </a>

    <p class="mt-4 mb-0">Feedbacks statistic within this month</p>

    <div class="row mb-2">
        {{-- Pending Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body" data-toggle="modal" data-target="#pendingOrderModal">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending Order (Click this to show list)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingOrderCount">{{ $pendingOrders->count() }}</div>
                        </div>
                        <div class="col-auto">
                             <img src="{{ asset('assets/img/requests.png') }}" alt="Order">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unprocessed Delivery --}}
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body" data-toggle="modal" data-target="#unprocessedOrderModal">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Unprocessed Delivery (Click this to show list)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $unprocessedOrders->count() }}
                                </div>

                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/bicycle.png') }}" alt="Biker">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Returnable Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body" data-toggle="modal" data-target="#returnableOrderModal">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Returnable Orders (Click this to show list)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnableOrders->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <img src="{{ asset('assets/img/requirement.png') }}" alt="Biker Application">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Total: <span id="ordersCount">{{ $orders->count()}}</span> (Orders)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ordered by</th>
                            <th>Location</th>
                            <th>Certificate Count</th>
                            <th>Total Price</th>
                            <th>Delivery Fee</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Ordered by</th>
                            <th>Location</th>
                            <th>Certificate Count</th>
                            <th>Total Price</th>
                            <th>Delivery Fee</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>Order #{{ $order->id }}</td>

                                <td>
                                    @if ($order->ordered_by != null)
                                        (#{{$order->ordered_by}}){{ $order->contact->getFullNameAttribute() }}
                                    @else
                                        {{ $order->name }}
                                    @endif
                                </td>

                                <td>{{ $order->location_address }}</td>

                                {{-- <td>
                                    <span>Email: <br>
                                        <strong> {{ $order->email }} </strong>
                                    </span>

                                    <br>
                                    <span>Phone No: <br>
                                        <strong> {{ $order->phone_no }} </strong>
                                    </span>
                                </td> --}}
                                <td>{{ $order->certificate_forms_count }}</td>
                                <td>{{ '₱'.$order->total_price }}</td>
                                <td>{{ '₱'.$order->delivery_fee }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y') }}</td>
                                <td>
                                    <p>Application: <br>
                                        <strong> {{ $order->application_status }} </strong>
                                    </p>

                                    <p>Pick up: <br>
                                        <strong> {{ $order->pick_up_type }} </strong>
                                    </p>

                                    <p>Order Status: <br>
                                        <strong> {{ $order->order_status }} </strong>
                                    </p>
                                </td>

                                {{-- <td>{{ $order->admin_message != null ? $order->admin_message : 'Not yet responded'}}</td> --}}

                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</td>

                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteOrder({{ $order->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>

                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.orders.show', $order->id) }}" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>

                            </tr>

                            {{-- <tr>
                                <td>{{$biker->id}}</td>
                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($biker->file_path) ? asset('storage/'.$biker->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$biker->getFullNameAttribute()}} image">
                                </td>

                                <td>{{$biker->getFullNameAttribute()}}</td>
                                <td>{{$biker->email}}</td>

                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($biker->latest_biker_request->credential_file_path) ? asset('storage/'.$biker->latest_biker_request->credential_file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$biker->getFullNameAttribute()}}'s bike">
                                </td>

                                <td>
                                    <p><span class="font-weight-bold">Type: </span> {{ $biker->bike_type }}</p>
                                    <p><span class="font-weight-bold">Color: </span> {{ $biker->bike_color }}</p>
                                    <p><span class="font-weight-bold">Size: </span> {{ $biker->bike_size }}</p>
                                </td>

                                <td>{{$biker->created_at}}</td>

                                <td>
                                    <ul class="list-inline m-0">

                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.bikers.profile', $biker->id) }}">
                                                View Transaction <i class="fas fa-eye"></i>
                                            </a>
                                        </li>

                                        <li class="list-item mb-1">
                                            <button class="btn btn-danger btn-sm btnDemote" onclick="demote({{$biker->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Demote Biker">
                                                <span class="btnText btnDemoteTxt">Demote</span>
                                                <i class="btnDemoteIcon fas fa-arrow-down ml-1"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr> --}}
                        @empty
                            <p>No orders yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
