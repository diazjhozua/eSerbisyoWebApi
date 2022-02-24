@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/report-types/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', $type->name)

@section('content')

    @include('admin.taskforce.reports.reportSelectModal')

    {{-- Report Modal --}}
    @include('admin.taskforce.reports.reportFormModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Report Type: {{ $type->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <input type="hidden" id="reportTypeID" value="{{ $type->id }}">
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
            <h6 class="m-0 font-weight-bold text-primary">Reports about {{ $type->name }} (Total: {{ $type->reports_count}} reports)</h6>
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
                            <th>Classification</th>
                            <th>Status</th>
                            <th>Reported Date</th>
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
                            <th>Classification</th>
                            <th>Status</th>
                            <th>Reported Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($type->reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>{{ $report->is_anonymous ? 'Anonymous User' :  $report->user->getFullNameAttribute(). '(#'. $report->user_id .')' }} </td>
                                @if ($type->id == 0)
                                    <td>{{ $report->custom_type }}</td>
                                @endif

                                <td>
                                    @if ($report->urgency_classification == 'Urgent')
                                        <p class="text-danger"><strong>{{ $report->urgency_classification }}</strong></p>
                                    @else
                                        <p class="text-warning"><strong>{{ $report->urgency_classification }}</strong></p>
                                    @endif
                                </td>

                                <td>
                                    @if ($report->status == 'Noted')
                                        <div class="p-2 bg-success text-white rounded-pill text-center">
                                    @elseif ($report->status == 'Pending')
                                        <div class="p-2 bg-warning text-white rounded-pill text-center">
                                    @elseif ($report->status == 'Ignored')
                                        <div class="p-2 bg-danger text-white rounded-pill text-center">
                                    @elseif ($report->status == 'Invalid')
                                        <div class="p-2 bg-secondary text-white rounded-pill text-center">
                                    @endif
                                        {{ $report->status }}
                                    </div>
                                </td>

                                <td>{{ $report->created_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="viewReport({{ $report->id}})" type="button" data-toggle="tooltip" data-placement="top" title="View">
                                                View Report
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
