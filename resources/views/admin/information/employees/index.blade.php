@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/employees/index.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Employees')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.employees.form')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createEmployee()">
        Create
    </button>

    <div class="row">
        {{-- Employee publish this day --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" >
                                Employee publish this day</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisDayCount">{{ $employeesData->this_day_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Employee publish this month --}}
        <div class="col-sm mt-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="thisMonthCount">
                                Employee publish this month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeesData->this_month_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employee publish this year Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" id="thisYearCount">
                                Employee publish this year</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employeesData->this_year_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3">These are the list of employees that are available for the residents to view.</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="employeesCount">{{ $employees->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Position</th>
                            <th>Term</th>
                            <th>Description</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Position</th>
                            <th>Term</th>
                            <th>Description</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>
                                    <img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="{{ asset('storage/'.$employee->file_path) }}" class="rounded" alt="{{$employee->name}} image">
                                </td>
                                @if (isset($employee->custom_position))
                                    <td>{{ $employee->custom_position }}</td>
                                @else
                                    <td><a href="{{route('admin.positions.show', $employee->position_id)}}">{{ $employee->position->name }}</a></td>
                                @endif

                                @if (isset($employee->custom_term))
                                    <td>{{ $employee->custom_term }}</td>
                                @else
                                    <td><a href="{{route('admin.terms.show', $employee->term_id)}}">{{ $employee->term->name }} ({{ $employee->term->year_start }}-{{ $employee->term->year_end }})</a></td>
                                @endif

                                <td>{{ $employee->description}}</td>

                                <td>{{ $employee->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="editEmployee({{ $employee->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteEmployee({{ $employee->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No employees yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection






