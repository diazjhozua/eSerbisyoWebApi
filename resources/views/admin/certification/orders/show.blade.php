@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/orders/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Checkout')

@section('content')

    @include('admin.certification.orders.formModal')

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


    </style>


    <p class="text-justify">
        These are the following details of this order which includes what the information of the resident who made this order, type of order, and other information
        related to this order. When proccessing order, make sure to check the credentials for each certificate they requested and modify the possible error in all certificates before
        making changes in any order status. See credentials -> Modify Certificate Forms (To see any mistakes and add additional fields (specially in Cedula)) -> Change Status -> Submit Message
    </p>

    <div class="row">

        <div class="col-md-4 col-sm-4">
            {{-- Order Form --}}
            <div class="card text-black mb-3">
                <div class="card-body">
                    <form name="orderForm" id="orderForm" enctype="multipart/form-data" novalidate>
                        {{-- Contact User ID / Name --}}
                        <div class="form-group">
                            <label for="inputOrderName">Name</label>
                            @if ($order->ordered_by == null)
                                <input type="text" class="form-control" name="inputOrderName" id="inputOrderName" value="{{ $order->name }}" disabled>
                            @else
                                <input type="text" class="form-control" name="inputOrderName" id="inputOrderName" value="(#{{$order->ordered_by}}) {{ $order->contact->getFullNameAttribute() }}" disabled>
                            @endif
                            <small class="form-text text-muted">Name of the specified customer</small>
                        </div>

                        {{-- Contact Information --}}
                        <div class="row mt-3 mt-lg-0">
                            <div class="col-12">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="inputOrderEmail">Email</label>
                                    <input type="text" class="form-control" name="inputOrderEmail" id="inputOrderEmail" value="{{ $order->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-12">
                                {{-- Phone No --}}
                                <div class="form-group">
                                    <label for="inputOrderPhone">Phone No</label>
                                    <input type="text" class="form-control" name="inputOrderPhone" id="inputOrderPhone"  value="{{ $order->phone_no }}" disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Location Address --}}
                        <div class="form-group">
                            <label for="inputOrderLocation">Location Address</label>
                            <textarea class="form-control" name="inputOrderLocation" id="inputOrderLocation" rows="2" disabled>{{ $order->location_address }}</textarea>
                        </div>

                        {{-- Pickup Type for walkin  --}}
                        <div class="form-group">
                            <label for="inputOrderName">Pickup Type</label>
                            <input type="text" class="form-control" name="inputOrderName" id="inputOrderName" value="{{ $order->pick_up_type }}" disabled>
                            <small class="form-text text-muted">Name of the specified customer</small>
                        </div>

                        @if ($order->pick_up_type == 'Walkin')
                            {{-- Pickup Date --}}
                            <div class="form-group">
                                <label for="inputPickupDate">Pickup Date</label>
                                <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="inputPickupDate" name="inputPickupDate" value="{{ $order->pickup_date }}" aria-label="Select Pickup Date" disabled>
                            </div>
                        @else
                            {{-- Pickup date for not walkin --}}
                            <label>Pickup Date</label>
                            <div class="input-group mb-2">
                                <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="inputPickupDate" name="inputPickupDate" value="{{ $order->pickup_date }}" aria-label="Select Pickup Date" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->order_status == 'DNR' ? 'disabled' : '' }}>
                                @if ($order->order_status != 'Received' && $order->application_status != 'Denied' && $order->order_status != 'DNR')
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="btnChangePickupDate">Change</button>
                                    </div>
                                @endif

                            </div>

                            {{-- Latest Admin Message --}}
                            <div class="form-group">
                                <label for="inputAdminMessage">Latest Admin Message</label>
                                <textarea class="form-control" name="inputAdminMessage" id="inputAdminMessage" rows="2" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->order_status == 'DNR' ? 'disabled' : '' }}>{{ $order->admin_message }}</textarea>
                                @if ($order->order_status != 'Received' && $order->application_status != 'Denied' && $order->order_status != 'DNR')
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary mt-2" type="button" id="btnChangeAdminMessage" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->order_status == 'DNR' ? 'disabled' : '' }}>Send Message</button>
                                    </div>
                                @endif
                            </div>
                        @endif

                            {{-- Received Date --}}
                            <div class="form-group">
                                <label for="inputPickupDate">Received Date</label>
                                 <input type="text" autocomplete="off" class="form-control datepicker" class="form-control" id="inputPickupDate" name="inputPickupDate" value="{{ $order->received_at }}" aria-label="Select Pickup Date" disabled>
                            </div>
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
            @if ($order->pick_up_type != 'Walkin')
                <div class="bikerContent">
                    <h5 class="mt-3 text-left">User Requirements</h5>

                    <div class="userProfile">
                        <div class="row">
                            <div class="col type">
                                Requirement:
                            </div>

                            <div class="col description">

                                @foreach ($passedRequirements as $passedRequirement)
                                    <p class="font-weight-bold p-0 m-0 text-left">
                                        <a href="{{route('admin.viewRequirement', ['fileName' => $passedRequirement['file_name']]) }}" target="_blank">
                                            {{ $passedRequirement['name'] }}
                                        </a>
                                    </p>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col type">
                                Requirement Status:
                            </div>

                            <div class="col description">
                                @if ($isCompleteRequirements)
                                    <p class="font-weight-bold p-0 m-0 text-left text-success">
                                        Complete
                                    </p>
                                @else
                                    <p class="font-weight-bold p-0 m-0 text-left text-warning">
                                        Incomplete
                                    </p>
                                @endif

                                @foreach ($noRequirements as $requirement)
                                    <p class="font-weight-bold p-0 m-0 text-left text-danger">
                                        {{ $requirement['name'] }}
                                    </p>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endif

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

            @if ($order->application_status == 'Approved')
                <a type="button" class="btn btn-outline-primary btn-sm btn-block m-2" id="printReceipt" href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank">Print Receipt</a>
            @endif

            <div class="row">
                <div class="col-md-6 col-sm-6">
                    {{-- APPLICATION STATUS --}}
                    <label class="ml-2">Application Status</label>
                    <div class="input-group mb-3 ml-2">
                        <select class="custom-select" id="inputSelApplicationStatus" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->pick_up_type == 'Walkin'  ? 'disabled' : ''}} >
                            <option value="{{ $order->application_status }}" selected>{{ $order->application_status }}</option>
                            @forelse ($applicationType as $application)
                                @if ($order->application_status != $application->type)
                                    <option value="{{ $application->type }}">{{ $application->type }} </option>
                                @endif
                            @empty
                                <option value="">Error! Please refresh the page</option>
                            @endforelse
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnChangeApplicationStatus" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->pick_up_type == 'Walkin'  ? 'disabled' : ''}}>Change</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    {{-- APPLICATION STATUS --}}
                    <label class="ml-2">Order Status</label>
                    <div class="input-group mb-3 ml-2">
                        <select class="custom-select" id="inputSelOrderStatus" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->pick_up_type == 'Walkin'  ? 'disabled' : ''}}>
                            <option value="{{ $order->order_status }}" selected>{{ $order->order_status }}</option>
                            @forelse ($orderType as $orderStats)
                                @if ($order->order_status != $orderStats->type)
                                    <option value="{{ $orderStats->type }}">{{ $orderStats->type }} </option>
                                @endif
                            @empty
                                <option value="">Error! Please refresh the page</option>
                            @endforelse
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnChangeOrderStatus" {{ $order->order_status == 'Received' || $order->application_status == 'Denied' || $order->pick_up_type == 'Walkin'  ? 'disabled' : ''}}>Change</button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($order->pick_up_type == 'Delivery')
                {{-- Payment --}}
                <label class="ml-2">Biker Payment Status</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputDeliveryPayment" {{ $order->application_status == 'Denied' || $order->delivery_payment_status == 'Received'  ? 'disabled' : ''}}>
                        <option value="{{ $order->delivery_payment_status }}" selected>{{ $order->delivery_payment_status }}</option>
                        @forelse ($deliveryPayments as $deliveryPayment)
                            @if ($order->delivery_payment_status != $deliveryPayment->type)
                                <option value="{{ $deliveryPayment->type }}">{{ $deliveryPayment->type }} </option>
                            @endif
                        @empty
                            <option value="">Error! Please refresh the page</option>
                        @endforelse
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btnDeliveryPayment" {{ $order->application_status == 'Denied' || $order->delivery_payment_status == 'Received'  ? 'disabled' : ''}}>Change</button>
                    </div>
                </div>
            @endif

            @if ($order->order_status == 'DNR')
                {{-- Biker returned delivery --}}
                <label class="ml-2">Biker returned the delivery item</label>
                <div class="input-group mb-3 ml-2">
                    <select class="custom-select" id="inputReturnItem">
                        <option value="{{ $order->is_returned }}" selected>{{ $order->is_returned }}</option>
                        @forelse ($isReturns as $isReturn)
                            @if ($order->is_returned != $isReturn->type)
                                <option value="{{ $isReturn->type }}">{{ $isReturn->type }} </option>
                            @endif
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
