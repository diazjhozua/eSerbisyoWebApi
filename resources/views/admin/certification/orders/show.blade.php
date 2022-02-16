@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/orders/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Checkout')

@section('content')

    @include('admin.certification.orders.formModal')
    @include('admin.certification.orders.modals.verifyApplication')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            </button>
                Order #{{ $order->id }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

        <input id="inputOrderID" value="{{ $order->id }}" hidden/>

        <style>

        .bikerContent {
            padding-left: 10px;
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
            margin: 10px;
            text-align: justify;
            color: black;
        }

        .orderInfo {
            margin-left: 10px;
            color: black;
        }

        .orderLabel {
            padding: 0;
            margin-bottom: 5px;
            color: rgb(110, 110, 110);
        }


    </style>

    <p class="text-justify">
        These are the following details of this order which includes what the information of the resident who made this order, type of order, and other information
        related to this order. When proccessing order, make sure to check the credentials for each certificate they requested and modify the possible error in all certificates before
        making changes in any order status. See credentials -> Modify Certificate Forms (To see any mistakes and add additional fields (specially in Cedula)) -> Verify Requirements -> Change Status.
    </p>

    {{-- <p class="text-justify">
        If the application status is marked as Cancelled, it is either the the resident who request the following certificate does not get
        picked by our biker 3 days prior to specified received date (Delivery Mode) or the resident didn't get the requested certificate in the barangay
        office (Pickup Mode).
    </p> --}}

    <div class="row">

        <div class="col-md-4 col-sm-4">
            {{-- Order Form --}}
            <div class="card text-black mb-3">
                <div class="card-body">
                    @if ($order->application_status == 'Approved')
                        <a type="button" class="btn btn-outline-primary btn-sm btn-block m-2" id="printReceipt" href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank">Print Receipt</a>
                    @endif

                    <form name="orderForm" id="orderForm" enctype="multipart/form-data" novalidate>
                        {{-- Contact User ID / Name --}}
                        <p class="orderLabel">Name of the specified customer</p>
                        @if ($order->ordered_by == null)
                            <p class="orderInfo" name="inputOrderName" id="inputOrderName">{{ $order->name }}</p>
                            {{-- <input type="text" class="form-control" name="inputOrderName" id="inputOrderName" value="{{ $order->name }}" disabled> --}}
                        @else
                            <p class="orderInfo" >(#{{$order->ordered_by}}) {{ $order->contact->getFullNameAttribute() }}</p>
                        @endif

                        {{-- Contact Information --}}
                        <div class="row mt-3 mt-lg-0">
                            <div class="col-12">
                                {{-- Email --}}
                                <p class="orderLabel">Email</p>
                                <p class="orderInfo" >{{ $order->email }}</p>
                            </div>

                            <div class="col-12">
                                {{-- Phone No --}}
                                <p class="orderLabel">Phone No</p>
                                <p class="orderInfo" >{{ $order->phone_no }}</p>
                            </div>
                        </div>

                        {{-- Location Address --}}
                        <p class="orderLabel">Location Address</p>
                        <p class="orderInfo" >{{ $order->location_address }}</p>

                        {{-- Order Type  --}}
                        <p class="orderLabel">Order Type</p>
                        <p class="orderInfo" >{{ $order->pick_up_type }}</p>


                        @if ($order->pick_up_type == "Walkin")
                            {{-- Since it is walkin the pickup date and received date is the same :) --}}
                            <p class="orderLabel">Requested/Received Date:</p>
                            <p class="orderInfo">{{ \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y') }}</p>
                            <p class="orderLabel text-center">Recorded: {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}</p>
                        @else

                            @if ($order->application_status != "Denied")
                                {{-- Pickup Date --}}
                                <p class="orderLabel">Assigned Pickup Date:</p>
                                <p class="orderInfo {{ $order->pickup_date == null ? 'text-primary' : '' }}" >{{ $order->pickup_date == null ? 'Not been set' : \Carbon\Carbon::parse($order->pickup_date)->format('F d, Y') }}</p>

                                {{-- Received Date --}}
                                <p class="orderLabel">Received Date:</p>
                                <p class="orderInfo {{ $order->received_at == null ? 'text-primary' : '' }}" >{{ $order->received_at == null ? 'Not been received' : \Carbon\Carbon::parse($order->received_at)->format('F d, Y') }}</p>
                            @endif

                            {{-- Application Status --}}
                            <p class="orderLabel">Application Status:</p>
                            @if ($order->application_status == 'Pending')
                                <p class="orderInfo text-primary">{{ $order->application_status }}</p>
                            @elseif ($order->application_status == 'Cancelled')
                                <p class="orderInfo text-warning">{{ $order->application_status }}</p>
                            @elseif ($order->application_status == 'Approved')
                                <p class="orderInfo text-success">{{ $order->application_status }}</p>
                            @elseif ($order->application_status == 'Denied')
                                <p class="orderInfo text-danger">{{ $order->application_status }}</p>
                            @endif


                            @if ($order->application_status != "Denied")
                                {{-- Order Status --}}
                                <p class="orderLabel">Order Status</p>
                                @if ($order->order_status == 'Pending' || $order->order_status == 'Waiting')
                                    <p class="orderInfo text-primary">{{ $order->order_status }}</p>
                                @elseif ($order->order_status == 'Accepted' || $order->order_status == 'On-Going')
                                    <p class="orderInfo text-info">{{ $order->order_status }}</p>
                                @elseif ($order->order_status == 'Received')
                                    <p class="orderInfo text-success">{{ $order->order_status }}</p>
                                @elseif ($order->order_status == 'DNR')
                                    <p class="orderInfo text-danger">{{ $order->order_status }} (Did Not Received) </p>
                                @endif
                            @endif

                            {{-- If the order is in delivery mode and the order is booked by the biker --}}
                            @if ($order->pick_up_type == 'Delivery')
                                @if ($order->order_status == 'Received')
                                    <p class="orderLabel">Order payment status </p>
                                    @if ($order->delivery_payment_status == 'Pending')
                                        <p class="orderInfo text-primary">The biker does not yet give the payment to the barangay</p>
                                    @else
                                        <p class="orderInfo text-success">The order has been processed</p>
                                    @endif
                                @elseif ($order->order_status == 'DNR')
                                    <p class="orderLabel">Biker returnable item</p>
                                    @if ($order->is_returned == 'No')
                                        <p class="orderInfo text-warning">The biker does not give back the item</p>
                                    @elseif($order->is_returned == 'Yes')
                                        <p class="orderInfo text-success">The order has been returned</p>
                                    @endif
                                @endif
                            @endif

                            {{-- Admin Message  --}}
                            <p class="orderLabel">Admin Respond</p>
                            <p class="orderInfo" >{{ $order->admin_message == null ? 'Not yet been submitted' : $order->admin_message }}</p>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Order Price --}}
            <div class="card text-black mb-2">
                <div class="card-body">
                    <div class="container">

                        @if ($order->pick_up_type == 'Delivery')

                            <div class="row">
                                <div class="col-sm">
                                    Certificate Price:
                                </div>

                                <div class="col-sm">
                                    <p class="font-weight-bold">₱ <span id="totalCertificatePrice">{{ $order->total_price }}</span> </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm">
                                    Delivery Fee:
                                </div>

                                <div class="col-sm">
                                    <p class="font-weight-bold">₱ <span id="totalDeliveryFee">{{ $order->delivery_fee }}</span> </p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm">
                                    Total Price:
                                </div>

                                <div class="col-sm">
                                    <p class="font-weight-bold">₱ <span id="totalPrice">{{ $order->total_price + $order->delivery_fee }}</span> </p>

                                </div>
                            </div>

                        @else
                            <div class="row">
                                <div class="col-sm">
                                    Total Price:
                                </div>

                                <div class="col-sm">
                                    <p class="font-weight-bold">₱ <span id="totalPrice">{{ $order->total_price }}</span> </p>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($order->pick_up_type == 'Delivery')
                <div class="bikerContent">
                    <h5 class="mt-3 text-left">Biker Information</h5>

                    @if ($order->delivered_by == null)
                        <h6>Not yet choosen by any biker</h6>
                    @else
                        <div class="userProfile">

                            <div class="row">
                                <div class="col type">
                                    Name
                                </div>

                                <div class="col description">
                                    <span class="font-weight-bold">(#{{$order->delivered_by}}){{ $order->biker->getFullNameAttribute() }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    Purok:
                                </div>

                                <div class="col">
                                    {{-- Purok --}}
                                    <span class="font-weight-bold">{{ $order->biker->purok->purok }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            Bike Type:
                                        </div>

                                        <div id="bike_type" class="col text-left">
                                            {{-- Bike Type --}}
                                            <span class="font-weight-bold">{{$order->biker->bike_type }}</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            Bike Color:
                                        </div>

                                        <div id="bike_color" class="col text-left">
                                            {{-- Bike Color --}}
                                            <span class="font-weight-bold">{{ $order->biker->bike_color }}</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            Bike Size:
                                        </div>

                                        <div id="bike_size" class="col text-left">
                                            {{-- Bike Size --}}
                                            <span class="font-weight-bold">{{ $order->biker->bike_size }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-md-8 col-sm-8">
            {{-- @if ($order->application_status == 'Approved') --}}
            @if ($order->application_status != 'Approved' && $order->application_status != 'Denied' &&  $order->application_status != 'Cancelled' && $order->pick_up_type != 'Walkin')
                <button type="button" class="btn btn-outline-success btn-sm btn-block m-2" id="verifyApplication" data-toggle="modal" data-target="#verifyApplicationModal">Verify Application</button>
            @endif

            {{-- if the application status is approved then they can modify order status --}}
            {{-- for pickup --}}
            @if ($order->pick_up_type == 'Pickup' && $order->application_status == 'Approved')
                {{-- Order Status --}}
                <label class="ml-2">Order Status</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputSelOrderStatus">
                        <option value="" selected>Select order status</option>
                        @forelse ($orderType as $orderStats)
                            <option value="{{ $orderStats->type }}">{{ $orderStats->type }} </option>
                        @empty
                            <option value="">Error! Please refresh the page</option>
                        @endforelse
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btnChangeOrderStatus">Change</button>
                    </div>
                </div>
            @endif

            {{-- For delivery --}}
            @if ($order->pick_up_type == 'Delivery' && $order->application_status == 'Approved'  && $order->order_status != 'Received' && $order->order_status != 'DNR')
                {{-- Order Status --}}
                <label class="ml-2">Order Status</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputSelOrderStatus">
                        <option value="" selected>Select order status</option>
                        @forelse ($orderType as $orderStats)
                            <option value="{{ $orderStats->type }}">{{ $orderStats->type }} </option>
                        @empty
                            <option value="">Error! Please refresh the page</option>
                        @endforelse
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btnChangeOrderStatus">Change</button>
                    </div>
                </div>
            @endif

            @if ($order->pick_up_type == 'Delivery' && $order->delivered_by != null && $order->order_status == "Received")
                {{-- Payment --}}
                <label class="ml-2">Biker Payment Status</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputDeliveryPayment">
                        <option value="" selected>
                            @if ($order->delivery_payment_status == 'Pending')
                                The biker does not yet give the payment to the barangay
                            @else
                                The order has been processed
                            @endif

                        </option>
                        @forelse ($deliveryPayments as $deliveryPayment)
                            <option value="{{ $deliveryPayment->type }}">{{ $deliveryPayment->type }} </option>
                        @empty
                            <option value="">Error! Please refresh the page</option>
                        @endforelse
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btnDeliveryPayment">Change</button>
                    </div>
                </div>
            @endif

            @if ($order->pick_up_type == 'Delivery' && $order->order_status == 'DNR')
                {{-- Biker returned delivery --}}
                <label class="ml-2">Biker returned the delivery item</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputReturnItem">
                        <option value="" selected>
                            @if ($order->is_returned == 'No')
                                <div class="orderInfo text-warning">The biker does not give back the item</div>
                            @elseif($order->is_returned == 'Yes')
                                <div class="orderInfo text-success">The order has been returned</div>
                            @endif
                        </option>
                        @forelse ($isReturns as $isReturn)
                            <option value="{{ $isReturn->type }}">{{ $isReturn->type }} </option>
                        @empty
                            <option value="">Error! Please refresh the page</option>
                        @endforelse
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btnReturnItem">Change</button>
                    </div>
                </div>
            @endif

            {{-- Order List--}}
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items:center py-3">
                    <h6 class="font-weight-bold text-primary">Form List (Total: <span id="ordersCount">{{ $order->certificateForms->count() }}</span> forms) </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="certificateFormTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Form ID</th>
                                    <th>Certificate</th>
                                    <th>Price</th>
                                    <th>Requirements</th>
                                    <th>General Information</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>Form ID</th>
                                    <th>Certificate</th>
                                    <th>Price</th>
                                    <th>Requirements</th>
                                    <th>General Information</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @forelse ($order->certificateForms as $certificateForm)
                                <tr>
                                    <td>{{ $certificateForm->id }}</td>
                                    <td>{{ $certificateForm->certificate->name }}</td>
                                    <td>{{ '₱'.$certificateForm->price_filled }}</td>
                                    <td>
                                            @foreach ($certificateForm->certificate->requirements as $requirement)
                                                <p><span class="font-weight-bold">{{ $requirement->name }} </span> </p>
                                            @endforeach
                                    </td>
                                    <td>
                                        <p><span class="font-weight-bold">First Name: </span> {{ $certificateForm->first_name }}</p>
                                        <p><span class="font-weight-bold">Middle Name: </span> {{ $certificateForm->middle_name }}</p>
                                        <p><span class="font-weight-bold">Last Name: </span> {{ $certificateForm->last_name }}</p>
                                        <p><span class="font-weight-bold">Address: </span> {{ $certificateForm->address }}</p>

                                        @if ($certificateForm->certificate_id !=5 && $certificateForm->certificate_id !=4)
                                            <p><span class="font-weight-bold">Civil Status: </span> {{ $certificateForm->civil_status }}</p>
                                            <p><span class="font-weight-bold">Birthday: </span> {{ $certificateForm->birthday }}</p>
                                            <p><span class="font-weight-bold">Citizenship: </span> {{ $certificateForm->citizenship }}</p>
                                        @endif

                                        @if ($certificateForm->certificate_id ==4)
                                            <p><span class="font-weight-bold">Civil Status: </span> {{ $certificateForm->civil_status }}</p>
                                            <p><span class="font-weight-bold">Birthday: </span> {{ $certificateForm->birthday }}</p>
                                        @endif

                                        @if ($certificateForm->certificate_id == 2 || $certificateForm->certificate_id == 4)
                                            <p><span class="font-weight-bold">Birthplace: </span> {{ $certificateForm->birthplace }}</p>
                                        @endif

                                        @switch($certificateForm->certificate_id)
                                            @case(1)
                                                <p><span class="font-weight-bold">Purpose: </span> {{ $certificateForm->purpose }}</p>
                                                @break
                                            @case(2)
                                            <p><span class="font-weight-bold">Sex: </span> {{ $certificateForm->sex }}</p>
                                            <p><span class="font-weight-bold">Birthday: </span> {{ $certificateForm->birthday }}</p>
                                            <p><span class="font-weight-bold">Citizenship: </span> {{ $certificateForm->citizenship }}</p>
                                            <p><span class="font-weight-bold">Cedula Type: </span> {{ $certificateForm->cedula_type }}</p>
                                            <p><span class="font-weight-bold">TIN: </span> {{ $certificateForm->tin_no }}</p>
                                            <p><span class="font-weight-bold">ICR Type: </span> {{ $certificateForm->icr_no }}</p>
                                            <p><span class="font-weight-bold">Civil Status: </span> {{ $certificateForm->civil_status }}</p>
                                            <p><span class="font-weight-bold">Profession: </span> {{ $certificateForm->profession }}</p>
                                            <p><span class="font-weight-bold">Basic Community Tax: </span> ₱ {{ $certificateForm->basic_tax }}</p>
                                            <p><span class="font-weight-bold">Additional Community Tax : </span> ₱ {{ $certificateForm->additional_tax }}</p>
                                            <p><span class="font-weight-bold">Interest: </span> ₱ {{ $certificateForm->interest }}</p>

                                            <p>
                                                <span class="font-weight-bold">Earnings derived from business during the preceding: </span>
                                                ₱ {{ $certificateForm->gross_receipt_preceding }}
                                            </p>

                                            <p>
                                                <span class="font-weight-bold">Earnings derived from exercise of profession: </span>
                                                ₱ {{ $certificateForm->gross_receipt_profession }}
                                            </p>

                                            <p>
                                                <span class="font-weight-bold">Income from real property: </span>
                                                ₱ {{ $certificateForm->real_property }}
                                            </p>
                                                @break

                                            @case(3)
                                                <p><span class="font-weight-bold">Purpose: </span> {{ $certificateForm->purpose }}</p>
                                                @break
                                            @case(4)
                                                <p><span class="font-weight-bold">Contact No: </span> {{ $certificateForm->contact_no }}</p>
                                                <p><span class="font-weight-bold">Contact Person: </span> {{ $certificateForm->contact_person }}</p>
                                                <p><span class="font-weight-bold">Contact Person No: </span> {{ $certificateForm->contact_person_no }}</p>
                                                <p><span class="font-weight-bold">Contact Person Relation: </span> {{ $certificateForm->contact_person_relation }}</p>
                                                @break
                                            @case(5)
                                                <p><span class="font-weight-bold">Business Name: </span> {{ $certificateForm->business_name }}</p>
                                                @break
                                        @endswitch
                                    </td>

                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm" onclick="editForm({{ $certificateForm->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </li>

                                            <li class="list-inline-item mb-1">
                                                <a class="btn btn-secondary btn-sm" href="{{ route('admin.certificates.printCertificate', $certificateForm->id) }}" target="_blank" type="button" data-toggle="tooltip" data-placement="top" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No submitted forms</p>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- END OF - Order List --}}

            {{-- If biker is delivered the biker --}}
            @if ($order->order_status == 'Received' && $order->pick_up_type == 'Delivery')
                <h1>Biker proof of delivery</h1>
                <img class="mt-2 mx-auto d-block" style="height:300px; width: 300px;" id="currentImage"
                   src="{{ isset($order->file_path) ? asset('storage/'.$order->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="biker proof of web">
            @endif

        </div>
    </div>

@endsection
