@extends('layouts.admin')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/bikers/profile.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Biker Profile')

@section('content')

    @include('admin.certification.bikers.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button>
            Biker:  (#{{ $user->id }}) {{ $user->getFullNameAttribute() }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>

        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <style>

        .bikerContent {
            padding: 20px;
            border-radius: 5px;
            border: none;
            text-align: center;
            font-size: 14px;
        }

        .picture-box img {
            text-align: center;
            width: 200px;
            height: 250px;
            border-radius: 30%;
            margin-bottom: 0px;
        }

        .bikeImg {
            text-align: center;
            width: 200px;
            height: 150px;
            border-radius: 30%;
            margin-bottom: 0px;
        }
        .userProfile {
            font-size: 15px;
            margin: 30px;
            text-align: justify;
            color: black;
        }


    </style>


    <div class="row">
        {{-- Biker's Profile --}}
        <div class="col">
            <div class="bikerContent">
                {{-- Biker's Profile --}}
                <div class="picture-box">
                    <img id="profilePicture" src="{{ isset($user->file_path) ? $user->file_path :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" alt="Ezekiel Lacbayen">
                </div>

                    <div class="userProfile">
                        <div class="row">
                            <div class="col type">
                                First Name:
                            </div>

                            <div id="firstName" class="col description">
                                <span class="font-weight-bold">{{ $user->first_name }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Middle Name:
                            </div>

                            <div id="middleName" class="col">
                                <span class="font-weight-bold">{{ $user->middle_name }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Last Name:
                            </div>

                            <div id="lastName" class="col">
                                {{-- Last Name --}}
                                <span class="font-weight-bold">{{ $user->last_name }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Purok:
                            </div>

                            <div id="purok" class="col">
                                {{-- Purok --}}
                                <span class="font-weight-bold">{{ $user->purok->purok }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                Address:
                            </div>

                            <div id="address" class="col text-left">
                                {{-- Address --}}
                                <span class="font-weight-bold">{{ $user->address }}</span>
                            </div>
                        </div>

                        <h5 class="mt-3">Bike Information</h5>

                        <div class="row">
                            <div class="col">
                                <img class="bikeImg" src="{{ isset($user->latest_biker_request) ? $user->latest_biker_request->credential_file_path :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" alt="Ezekiel Lacbayen">
                            </div>

                            <div class="col mt-5">
                                <div class="row">
                                    <div class="col">
                                        Bike Type:
                                    </div>

                                    <div id="bike_type" class="col text-left">
                                        {{-- Bike Type --}}
                                        <span class="font-weight-bold">{{ $user->bike_type }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        Bike Color:
                                    </div>

                                    <div id="bike_color" class="col text-left">
                                        {{-- Bike Color --}}
                                        <span class="font-weight-bold">{{ $user->bike_color }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        Bike Size:
                                    </div>

                                    <div id="bike_size" class="col text-left">
                                        {{-- Bike Size --}}
                                        <span class="font-weight-bold">{{ $user->bike_size }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
            </div>
        </div>
        {{-- End of biker's profile --}}

        {{-- Transaction History --}}
        <div class="col">
                <!-- DataTales Example -->
            <div class="card shadow mt-2 mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Delivered History (Total: <span id="typeCount">
                        {{ $user->deliverySuccess->count() }}
                    </span>)</h6>
                    <h6 class="mt-2 mb-0 font-weight-bold text-primary">Total Earnings:
                        ₱ {{ floatval($user->deliverySuccess->sum('delivery_fee')) }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableRecord" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Ordered by</th>
                                    <th>Location</th>
                                    <th>Total Price</th>
                                    <th>Delivery Fee</th>
                                    <th>Pickup Date</th>
                                    <th>Delivered Date</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Ordered by</th>
                                    <th>Location</th>
                                    <th>Total Price</th>
                                    <th>Delivery Fee</th>
                                    <th>Pickup Date</th>
                                    <th>Delivered Date</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            @if ($order->ordered_by != null)
                                                (#{{$order->delivered_by}}){{ $order->contact->getFullNameAttribute() }}
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
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</td>

                                        <td>
                                            <ul class="list-inline m-0">
                                                <li class="list-inline-item mb-1">
                                                    <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.orders.show', $order->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <p>No orders yet</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
