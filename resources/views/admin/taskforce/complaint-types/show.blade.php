@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/complaint-types/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', $type->name)

@section('content')

    @include('admin.taskforce.complaints.reportSelectModal')
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Comnplaint Type: {{ $type->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
            class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
    </div>

    <a class="btn btn-primary" href="{{ route('admin.complaints.create') }}">
        Create
    </a>

    <input type="hidden" id="complaintTypeID" value="{{ $type->id }}">
    <p class="font-weight-light">Created at: {{ $type->created_at }} -- Updated at: {{ $type->updated_at }}</p>
    <p class="font-weight-light"></p>

    <div class="row">
        {{-- New Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthPendingCount">{{ $type->pending_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Noted Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Noted Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthNotedCount">{{ $type->noted_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Invalid Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Invalid Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthInvalidCount">{{ $type->invalid_count }}</div>
                            </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ingored Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ignored Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthIgnoredCount">{{ $type->ignored_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trash-alt fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Complaints about {{ $type->name }} (Total: <span id="complaintsCount">{{ $type->complaints->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
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
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
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
                        @forelse ($type->complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>{{ $complaint->user->getFullNameAttribute(). '(#'. $complaint->user_id .')' . '(Role: '. $complaint->user->user_role->role. ' )' }} </td>

                                @if ($type->id == 0)
                                    <td>{{ $complaint->custom_type }}</td>
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
                            <p>No reports yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
