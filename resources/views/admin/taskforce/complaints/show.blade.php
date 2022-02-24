@extends('layouts.admin')

@section('page-js')

    <!-- you load jquery somewhere before jSignature ... -->
    {{-- <script src="{{ asset('signature_pad/signature_pad.js') }}"></script>
    <script src="{{ asset('signature_pad/app.js') }}"></script> --}}

    {{-- Custom Scripts for this blade --}}


    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
        <script src="{{ asset('js/admin/taskforce/complaints/show.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', 'Complaint Details')

@section('content')

    {{-- Included Modals --}}
    @include('admin.taskforce.inc.changeStatusFormModal')
    @include('admin.taskforce.complaints.complainantForm')
    @include('admin.taskforce.complaints.defendantForm')
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i>
            </button>
            </button> (<span id="currentComplaintType">{{ $complaint->custom_type != null ? $complaint->custom_type: $complaint->type->name }}</span>) Complaint:
            (#{{ $complaint->id}})</span>
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>

            {{-- Edit --}}
            <button class="btn btn-primary btn-sm" onclick="editComplaint({{ $complaint->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                <i class="fas fa-edit"></i>
            </button>

            {{-- Delete --}}
            <button class="btn btn-danger btn-sm" type="button" onclick="deleteComplaint({{ $complaint->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                <i class="fas fa-trash-alt"></i>
            </button>

            {{-- Change Statuus --}}
            <button class="btn btn-info btn-sm" type="button" onclick="changeStatusComplaint({{ $complaint->id }})" data-toggle="tooltip" data-placement="top" title="Status">
                Change Status
            </button>
        </h1>
    </div>

    <p class="text-justify">
        View the details (Click the edit icon to edit the fields)
    </p>

    <div class="row">
        <div class="col-md-4 col-sm-4">
            {{-- Complaint --}}
            <div class="card text-black mb-3">
                <div class="card-body">

                        <p class="card-text"><strong>Submitted By: {{ $complaint->user->getFullNameAttribute() }} #{{ $complaint->user->id }} - ({{ $complaint->user->user_role->role }}) </strong></p>
                        <p class="card-text"><strong>Contact User: <span id="currentContactName">{{ $complaint->contact->getFullNameAttribute() }}</span> #<span id="currentContactID">{{ $complaint->contact->id }}</span> - (<span id="currentContactRole">{{ $complaint->contact->user_role->role }}</span>) </strong></p>
                        <p class="card-text"><strong>Latest Admin Message: </strong></p>
                        <p class="card-text"><small class="text-black" id="currentAdminMessage">{{ $complaint->admin_message }}</small></p>
                        <p class="card-text"><small class="text-muted">Submitted at: <span id="currentCreatedAt"> {{ $complaint->created_at }}</span> || Updated at: <span id="currentUpdatedAt">{{ $complaint->updated_at }}</span></small></p>

                        <div class="form-group">
                            <label>Status</label>
                            @if ($complaint->status == 'Pending')
                                <div id="currentStatus" class="p-2 bg-info text-white rounded-pill text-center">
                            @elseif ($complaint->status == 'Approved')
                                <div id="currentStatus" class="p-2 bg-dark text-white rounded-pill text-center">
                            @elseif ($complaint->status == 'Resolved')
                                <div id="currentStatus" class="p-2 bg-success text-white rounded-pill text-center">
                            @elseif ($complaint->status == 'Denied')
                                <div id="currentStatus" class="p-2 bg-danger text-white rounded-pill text-center">
                            @endif
                                {{ $complaint->status }}
                            </div>
                        </div>
                    <form name="complaintForm" id="complaintForm" action="{{ route('admin.complaints.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        {{-- Complaint Type Select Option --}}
                        <div class="form-group">
                             <div id="complaintFormMethod"></div>

                            <label for="type_id">Complaint Type</label>
                            <select class="custom-select" name="type_id" id="type_id" disabled>
                                @if ($complaint->type_id != null)
                                    <option value="{{ $complaint->type_id }}" selected>{{ $complaint->type->name }}</option>
                                @else
                                    <option value="" selected>Select type</option>
                                @endif
                            </select>
                            <small class="form-text text-muted">Please create new complaint type. If the user's complaint type does not match any</small>
                        </div>

                        {{-- CUSTOM TYPE --}}
                        <div class="form-group">
                            <label for="custom_type">Custom Type</label>
                            <input type="text" class="form-control" name="custom_type" id="custom_type" value="{{ $complaint->custom_type }}" disabled>
                             <small class="form-text text-muted">Custom type (If the user does not find complaint type that relates to their complaint)</small>
                        </div>


                        {{-- Contact User ID --}}
                        <div class="form-group">
                            <label for="contact_user_id">Contact (USER ID) </label>
                            <input type="number" class="form-control" name="contact_user_id" id="contact_user_id" value="{{ $complaint->contact_user_id }}" disabled>
                             <small class="form-text text-muted">The specified user_id will receive notification within the application</small>
                        </div>

                        {{-- Contact Information --}}
                        <div class="row mt-3 mt-lg-0">
                            <div class="col-sm-6">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" value="{{ $complaint->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                {{-- Phone No --}}
                                <div class="form-group">
                                    <label for="phone_no">Phone No</label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ $complaint->phone_no }}" disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" name="reason" id="reason" rows="5" disabled>{{ $complaint->reason }}</textarea>
                            <small class="form-text text-muted">Input here the reason why you are complaining</small>
                        </div>

                        {{-- Action --}}
                        <div class="form-group">
                            <label for="action">Action</label>
                            <textarea class="form-control" name="action" id="action" rows="5" disabled>{{ $complaint->action }}</textarea>
                            <small class="form-text text-muted">Input here the action you needed to resolve this conflict</small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onClick="cancelEditing()" id="cancelEditBtn" data-dismiss="modal" hidden>Cancel</button>

                            <button type="submit" class="btn btn-primary btnComplaintFormSubmit" hidden>
                                <i class="btnComplaintFormLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                <span class="btnComplaintFormTxt">Submit</span>
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
                    <h6 class="font-weight-bold text-primary">Complainant List (Total: <span id="complainantsCount">{{ $complaint->complainants_count }}</span> complainant) </h6>
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
                                    <th>ID</th>
                                    <th>Picture</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse ($complaint->complainants as $complainant)
                                    <tr>
                                        <td>{{ $complainant->name }}</td>

                                        <td><img src="{{ $complainant->file_path }}" class="rounded" alt="Jhozua Manguera Diaz signature" style="height: 100px; width: 100%;"></td>

                                        <td>
                                            <ul class="list-inline m-0">
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-primary btn-sm" onclick="editComplainant({{$complainant->id}}) " type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fas fa-edit" aria-hidden="true">
                                                        </i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item mb-1">
                                                    <button class="btn btn-danger btn-sm" onclick="deleteComplainant({{$complainant->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <p>No complainants yet submitted</p>
                                @endforelse
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
                    <h6 class="font-weight-bold text-primary">Defendant List (Total: <span id="defendantsCount">{{ $complaint->defendants_count }}</span> defendant) </h6>
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
                            @forelse ($complaint->defendants as $defendant)
                                <tr>
                                    <td>{{ $defendant->name }}</td>

                                    <td>
                                        <ul class="list-inline m-0">
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm" onclick="editDefendant({{$defendant->id}}) " type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-danger btn-sm" onclick="deleteDefendant({{$defendant->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <p>No complainants yet submitted</p>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection






