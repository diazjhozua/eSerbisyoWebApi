@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/orders/create.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Record Order')

@section('content')

    {{-- Included Forms --}}
    @include('admin.certification.orders.formModal')
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i>
            </button>
                Record Order
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <p class="text-justify">
        Fill up the following fields.
    </p>

    <div class="row">
        <div class="col-md-4 col-sm-4">
            {{-- Order Form --}}
            <div class="card text-black mb-3">
                <div class="card-body">
                    <form name="orderForm" id="orderForm" action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        {{-- Contact User ID --}}
                        <div class="form-group">
                            <label for="inputOrderName">Name</label>
                            <input type="text" class="form-control" name="inputOrderName" id="inputOrderName">
                            <small class="form-text text-muted">Name of the specified customer</small>
                        </div>

                        {{-- Contact Information --}}
                        <div class="row mt-3 mt-lg-0">
                            <div class="col-sm-6">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="inputOrderEmail">Email</label>
                                    <input type="text" class="form-control" name="inputOrderEmail" id="inputOrderEmail">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                {{-- Phone No --}}
                                <div class="form-group">
                                    <label for="inputOrderPhone">Phone No</label>
                                    <input type="text" class="form-control" name="inputOrderPhone" id="inputOrderPhone">
                                </div>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="form-group">
                            <label for="inputOrderLocation">Location Address</label>
                            <textarea class="form-control" name="inputOrderLocation" id="inputOrderLocation" rows="2"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="btnOrderFormSubmit" class="btn btn-primary">
                                <i id="btnOrderFormLoadingIcon" class="fa fa-spinner fa-spin" hidden></i>
                                <span id="btnOrderFormTxt">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Order Form --}}
            <div class="card text-black">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                Total Price:
                            </div>

                            <div class="col-sm">
                                <p class="font-weight-bold">₱ <span id="totalPrice">0</span> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-sm-8">
            {{-- INPUT SELECT WHAT TYPE OF CERTIFICATE TO ADD --}}
            <div class="input-group mb-3 p-2">
                <select class="custom-select" id="inputSelCertificate">
                    <option value="empty" selected>Choose certificate...</option>
                    @forelse ($certificates as $certificate)
                        <option value="{{ $certificate->id }}|{{ $certificate->price }}">{{ $certificate->name }} ₱ {{ $certificate->price }} </option>
                    @empty
                        <option value="">No certificates available</option>
                    @endforelse
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="btnAddCertificate">Add</button>
                </div>
            </div>
            {{-- END OF - INPUT SELECT WHAT TYPE OF CERTIFICATE TO ADD --}}

            {{-- Order List--}}
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items:center py-3">
                    <h6 class="font-weight-bold text-primary">Order List (Total: <span id="ordersCount">0</span> order) </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Certificate</th>
                                    <th>Price</th>
                                    <th>General Information</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>Certificate</th>
                                    <th>Price</th>
                                    <th>General Information</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- END OF - Order List --}}
        </div>
    </div>

@endsection
