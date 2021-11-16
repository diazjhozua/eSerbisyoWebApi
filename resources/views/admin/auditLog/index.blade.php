{{-- Audit Logs Page for all admins --}}
@extends('layouts.admin')

@section('page-js')
    <script src="{{ asset('js/admin/auditLog/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Audit Logs')

@section('content')

    {{-- Included Modals --}}
    @include('admin.auditLog.reportSelectModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Audit Logs
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
            class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
    </div>

    <span>Audited logs within this month</span>
    <div class="row">
        {{-- Created Models Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Created Models</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logsData->this_month_created_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-square fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Updated Models Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Updated Models</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logsData->this_month_updated_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deleted Models Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Deleted Models</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $logsData->this_month_deleted_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trash fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <p class="m-3 text-justify">{{ $info }}
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: )</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Model</th>
                            <th>Events</th>
                            <th>Executed by</th>
                            <th>Position</th>
                            <th>Model ID</th>
                            <th>Properties</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Model</th>
                            <th>Events</th>
                            <th>Executed by</th>
                            <th>Position</th>
                            <th>Model ID</th>
                            <th>Properties</th>
                            <th>Date</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>

                                <td>{{ str_replace('App\\Models\\', "",$log->subject_type);  }}</td>
                                <td>
                                    @switch($log->event)
                                        @case('created')
                                            <div class="p-2 mb-2 bg-success text-white rounded text-left">
                                                {{ strtoupper($log->event).' - '.$log->description }}
                                            </div>
                                            @break
                                        @case('updated')
                                            <div class="p-2 mb-2 bg-info text-white rounded text-left">
                                                {{ strtoupper($log->event).' - '.$log->description }}
                                            </div>

                                            @break
                                        @case('deleted')
                                            <div class="p-2 mb-2 bg-warning text-white rounded text-lefty">
                                                {{ strtoupper($log->event).' - '.$log->description }}
                                            </div>

                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $log->causer->first_name .' '. $log->causer->last_name }}</td>
                                <td>
                                    @if ($log->causer->user_role_id == 1)
                                        {{-- FOR SUPER ADMIN --}}
                                        <div class="p-2 bg-success text-white rounded-pill text-center">
                                            {{ $log->causer->user_role->role }}<i class="ml-1 fas fa-crown"></i>
                                        </div>
                                    @elseif ($log->causer->user_role_id > 1 && $log->causer->user_role_id < 5)
                                        {{-- FOR ADMINS --}}
                                        <div class="p-2 bg-primary text-white rounded-pill text-center">
                                            {{ $log->causer->user_role->role }}<i class="ml-1 fas fa-user-shield"></i>
                                        </div>
                                    @elseif ($log->causer->user_role_id > 4 && $log->causer->user_role_id < 8)
                                        {{-- FOR STAFFS --}}
                                        <div class="p-2 bg-info text-white rounded-pill text-center">
                                            {{ $log->causer->user_role->role }}<i class="ml-1 fas fa-user-cog"></i>
                                        </div>
                                    @elseif ($log->causer->user_role_id == 8)
                                        {{-- FOR BIKERS --}}
                                        <div class="p-2 bg-secondary text-white rounded-pill text-center">
                                            {{ $log->causer->user_role->role }}<i class="ml-1 fas fa-biking"></i>
                                        </div>

                                    @elseif ($log->causer->user_role_id == 9)
                                        {{-- FOR RESIDENT --}}
                                        <div class="p-2 bg-secondary text-white rounded-pill text-center">
                                            {{ $log->causer->user_role->role }}<i class="ml-1 fas fa-user"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>{{ $log->subject_id }}</td>
                                <td>
                                    @if (isset($log->properties['attributes']))
                                        <div class="m-1">
                                            <ul class="list-unstyled">
                                                <li>Attributes:
                                                    <ul>
                                                        @foreach ($log->properties['attributes'] as $key => $node)
                                                            <li>{{ $key }}: {{ $node }}</li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    @if (isset($log->properties['old']))
                                        <div class="m-1">
                                            <ul class="list-unstyled">
                                                <li>Old :
                                                    <ul>
                                                        @foreach ($log->properties['old'] as $key => $node)
                                                            <li>{{ $key }}: {{ $node }}</li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                </td>
                                <td>{{ $log->created_at }}</td>

                                {{-- @if ($feedback->type_id != 0)
                                    <td><a href="{{ route('admin.feedback-types.show', $feedback->type_id) }}">{{ $feedback->type->name }}</a></td>
                                @else
                                    <td>Others/Deleted- {{ $feedback->custom_type }}</td>
                                @endif

                                <td>{{ $feedback->polarity}}</td>
                                <td>{{ $feedback->message}}</td>
                                @if (!empty($feedback->admin_respond))
                                    <td>{{ $feedback->admin_respond }}</td>
                                @else
                                    <td>Not yet responded</td>
                                @endif

                                <td>{{ $feedback->status}}</td>
                                <td>{{ $feedback->created_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="sendRespond({{ $feedback->id }})" data-toggle="tooltip" data-placement="top" title="Respond" {{$feedback->status != 'Pending' ? 'disabled' : ''}}>
                                                Respond
                                            </button>
                                        </li>
                                    </ul>
                                </td> --}}
                            </tr>

                        @empty
                            <p>No logs yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection






