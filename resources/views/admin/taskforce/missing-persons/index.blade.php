@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/taskforce/missing-persons/index.js')}}"></script>

@endsection

{{-- Title Page --}}
@section('title', 'Missing Person Reports')

@section('content')

    {{-- Included Modals --}}

    {{-- Create/Edit --}}
    @include('admin.taskforce.missing-persons.formModal')

    @include('admin.taskforce.missing-persons.reportSelectModal')

    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Missing Person Reports
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal">
                <i class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <p class="text-justify">
        These reports was submitted by the residents through sending a report in the e-serbisyo android application.
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createReport()">
        Create
    </button>

    <h6 class="mt-2">Missing Person Reports statistic within this month (Total: <span id="thisMonthCount"> {{ $missingPersonsData->missing_persons_count}}</span>)</h6>

    <div class="row">
        {{-- New Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthPendingCount">{{ $missingPersonsData->pending_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approved Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Approved Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthApprovedCount">{{ $missingPersonsData->approved_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resolved Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved Report</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthResolvedCount"> {{ $missingPersonsData->resolved_count }}</div>
                            </div>
                        <div class="col-auto">
                            <i class="fab fa-resolving fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Denied Report Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Denied Report</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisMonthDeniedCount">{{ $missingPersonsData->denied_count }}</div>
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
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="reportsCount">{{ $missing_persons->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width:100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            <th>Type</th>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Last Seen</th>
                            <th>User Contact</th>
                            <th>Contact Information</th>
                            <th>Status</th>
                            <th>Reported Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Submitted By</th>
                            <th>Type</th>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Last Seen</th>
                            <th>User Contact</th>
                            <th>Contact Information</th>
                            <th>Status</th>
                            <th>Reported Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($missing_persons as $missing_person)
                            <tr>
                                <td>{{ $missing_person->id }}</td>
                                <td>{{ $missing_person->user->getFullNameAttribute(). '(#'. $missing_person->user_id .')' . '(Role: '. $missing_person->user->user_role->role. ' )' }} </td>
                                <td>
                                    @if ($missing_person->report_type == 'Missing')
                                        <p class="text-warning"><strong>{{ $missing_person->report_type }}</strong></p>
                                    @else
                                        <p class="text-info"><strong>{{ $missing_person->report_type }}</strong></p>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{$missing_person->file_path}}" target="_blank">
                                         <img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="{{ $missing_person->file_path }}" class="rounded" alt="{{$missing_person->missing_name}} image">
                                    </a>
                                </td>

                                <td>{{ $missing_person->name }}</td>
                                <td>{{ $missing_person->age.' yr old' }}</td>
                                <td>{{ $missing_person->height.' '. $missing_person->height_unit }}</td>
                                <td>{{ $missing_person->weight.' '. $missing_person->weight_unit }}</td>
                                <td>{{ $missing_person->last_seen }}</td>

                                @if ($missing_person->user_id === $missing_person->contact_user_id)
                                    <td> Same user </td>
                                @else
                                    <td>{{ $missing_person->contact->getFullNameAttribute(). '(#'. $missing_person->contact_user_id .')' . '(Role: '. $missing_person->contact->user_role->role . ' )' }} </td>
                                @endif

                                <td>
                                    <span>Email: <br>
                                        <strong> {{ $missing_person->email }} </strong>
                                    </span>

                                    <br>
                                    <span>Phone No: <br>
                                        <strong> {{ $missing_person->phone_no }} </strong>
                                    </span>
                                </td>



                                <td class="tdStatus">
                                    @if ($missing_person->status == 'Pending')
                                        <div class="p-2 bg-info text-white rounded-pill text-center">
                                    @elseif ($missing_person->status == 'Approved')
                                        <div class="p-2 bg-dark text-white rounded-pill text-center">
                                    @elseif ($missing_person->status == 'Resolved')
                                        <div class="p-2 bg-success text-white rounded-pill text-center">
                                    @elseif ($missing_person->status == 'Denied')
                                        <div class="p-2 bg-danger text-white rounded-pill text-center">
                                    @endif
                                        {{ $missing_person->status }}
                                    </div>
                                </td>

                                <td class="tdCreatedAt">{{ $missing_person->created_at }}</td>

                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.missing-persons.show', $missing_person->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>

                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="editReport({{ $missing_person->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteReport({{ $missing_person->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No reports yet submitted</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection






