@extends('layouts.admin')

@section('page-js')

    <!-- you load jquery somewhere before jSignature ... -->
    {{-- <script src="{{ asset('signature_pad/signature_pad.js') }}"></script>
    <script src="{{ asset('signature_pad/app.js') }}"></script> --}}

    {{-- Custom Scripts for this blade --}}


    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="{{ asset('js/admin/taskforce/complaints/create.js')}}"></script>

@endsection


{{-- Title Page --}}
@section('title', 'Record Complaint')

@section('content')

    {{-- Included Modals --}}
    @include('admin.taskforce.complaints.complainantForm')
    @include('admin.taskforce.complaints.defendantForm')
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i>
            </button>
            Create Complaint
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <p class="text-justify">
        Fill up the following fields.
    </p>

    <div class="row">
        <div class="col-md-4 col-sm-4">
            {{-- Complaint --}}
            <div class="card text-black mb-3">
                <div class="card-body">

                    <form name="reportForm" id="reportForm" action="{{ route('admin.complaints.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        {{-- Complaint Type Select Option --}}
                        <div class="form-group">
                            <label for="type_id">Complaint Type</label>
                            <select class="custom-select" name="type_id" id="type_id">
                                <option value="" selected>Select complaint type</option>
                                @forelse ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @empty
                                    <option value="">No complaint type added</option>
                                @endforelse
                            </select>
                            <small class="form-text text-muted">Please create new complaint type. If the user's complaint type does not match any</small>
                        </div>

                        {{-- Contact User ID --}}
                        <div class="form-group">
                            <label for="contact_user_id">Contact (USER ID) </label>
                            <input type="number" class="form-control" name="contact_user_id" id="contact_user_id">
                             <small class="form-text text-muted">The specified user_id will receive notification within the application</small>
                        </div>

                        {{-- Contact Information --}}
                        <div class="row mt-3 mt-lg-0">
                            <div class="col-sm-6">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                {{-- Phone No --}}
                                <div class="form-group">
                                    <label for="phone_no">Phone No</label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no">
                                </div>
                            </div>
                        </div>



                        {{-- Reason --}}
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" name="reason" id="reason" rows="5"></textarea>
                            <small class="form-text text-muted">Input here the reason why you are complaining</small>
                        </div>

                        {{-- Action --}}
                        <div class="form-group">
                            <label for="action">Action</label>
                            <textarea class="form-control" name="action" id="action" rows="5"></textarea>
                            <small class="form-text text-muted">Input here the action you needed to resolve this conflict</small>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btnReportFormSubmit">
                                <i class="btnReportFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                <span class="btnReportFormTxt">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-4 col-sm-4">
            {{-- Complainant--}}
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items:center py-3">
                    <h6 class="font-weight-bold text-primary">Complainant List (Total: <span id="complainantsCount">0</span> complainant) </h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" onclick="addComplainant()" data-toggle="tooltip" data-placement="top" title="Add Complainant">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableComplainant" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Signature</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Signature</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-4">
            {{-- Defendant Form --}}
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items:center py-3">
                    <h6 class="font-weight-bold text-primary">Defendant List (Total: <span id="defendantsCount">0</span> defendant) </h6>
                        <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" onclick="addDefendant()" data-toggle="tooltip" data-placement="top" title="Add Defendant">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableDefendant" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>

                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        <tbody>

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection






