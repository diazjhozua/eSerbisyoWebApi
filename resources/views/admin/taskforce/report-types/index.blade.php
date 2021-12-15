@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/taskforce/report-types/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Report Types')

@section('content')

    {{-- Included Modals --}}

    {{-- Create/Edit --}}
    @include('admin.types.form')

    {{-- Report Modal --}}
    @include('admin.types.report')

    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Report Types
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif

    </div>

    <p class="text-justify">
        Report type can be classified as a type of report where you residents from android application can choose what type of report they are reporting for the taskforce staff to view the
        said report. These are the following list of report types created by the admins. (When you delete a specific type of report, all of the reports
        related to that type would be transfer in the "Others" section)
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createType()">
        Create
    </button>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="typeCount">{{ $types->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Report Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Report Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->reports_count}}</td>
                                <td>{{ $type->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.report-types.show', $type->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        @if ($type->id !== 0)
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm" onclick="editType({{ $type->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-danger btn-sm" type="button" onclick="deleteType({{ $type->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </li>
                                        @endif

                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No report types created</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection






