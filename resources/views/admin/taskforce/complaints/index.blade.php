@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/taskforce/complaints/index.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', 'Complaints')

@section('content')

    {{-- Included Modals --}}

    @include('admin.taskforce.complaints.reportSelectModal')
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Complaints
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <p class="text-justify">
        These complaints was submitted by the residents through sending a Complaint in the e-serbisyo android application.
    </p>

    <!-- Button trigger modal -->
    <a class="btn btn-primary" href="{{ route('admin.complaints.create') }}">
        Create
    </a>

    <h6 class="mt-2">Complaints statistic within this month (Total: <span id="thisMonthCount"> {{ $complaintsData->complaints_count }}</span>)</h6>

    <div class="row">
        {{-- New Complaint Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Complaint</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthPendingCount">{{ $complaintsData->pending_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approved Complaint Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Approved Complaint</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthApprovedCount">{{ $complaintsData->approved_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resolved Complaint Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved Complaint</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthResolvedCount"> {{ $complaintsData->resolved_count }}</div>
                            </div>
                        <div class="col-auto">
                            <i class="fab fa-resolving fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Denied Complaint Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Denied Complaint</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthDeniedCount">{{ $complaintsData->denied_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="complaintsCount">{{ $complaints->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width:100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Action</th>
                            <th>User Contact</th>
                            <th>Contact Information</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Action</th>
                            <th>User Contact</th>
                            <th>Contact Information</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>{{ $complaint->user->getFullNameAttribute(). '(#'. $complaint->user_id .')' . '(Role: '. $complaint->user->user_role->role. ' )' }} </td>

                                @if (isset($complaint->custom_type))
                                    <td>{{ $complaint->custom_type }}</td>
                                @else
                                    <td><a href="{{route('admin.complaint-types.show', $complaint->type_id)}}">{{ $complaint->type->name }}</a></td>
                                @endif

                                <td>{{ $complaint->reason }}</td>
                                <td>{{ $complaint->action }}</td>

                                @if ($complaint->user_id === $complaint->contact_user_id)
                                    <td> Same user </td>
                                @else
                                    <td>{{ $complaint->contact->getFullNameAttribute(). '(#'. $complaint->contact_user_id .')' . '(Role: '. $complaint->contact->user_role->role . ' )' }} </td>
                                @endif

                                <td>
                                    <span>Email: <br>
                                        <strong> {{ $complaint->email }} </strong>
                                    </span>

                                    <br>
                                    <span>Phone No: <br>
                                        <strong> {{ $complaint->phone_no }} </strong>
                                    </span>
                                </td>

                                <td class="tdStatus">
                                    @if ($complaint->status == 'Pending')
                                        <div class="p-2 bg-info text-white rounded-pill text-center">
                                    @elseif ($complaint->status == 'Approved')
                                        <div class="p-2 bg-dark text-white rounded-pill text-center">
                                    @elseif ($complaint->status == 'Resolved')
                                        <div class="p-2 bg-success text-white rounded-pill text-center">
                                    @elseif ($complaint->status == 'Denied')
                                        <div class="p-2 bg-danger text-white rounded-pill text-center">
                                    @endif
                                        {{ $complaint->status }}
                                    </div>
                                </td>

                                <td class="tdCreatedAt">{{ $complaint->created_at }}</td>

                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.complaints.show', $complaint->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteComplaint({{ $complaint->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            {{-- <p>No complaints yet submitted</p> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection






