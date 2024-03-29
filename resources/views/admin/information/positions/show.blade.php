@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/positions/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', $position->name)

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.employees.form')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Position: {{ $position->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Report</a> --}}
    </div>

    <p class="font-weight-light">Created at: {{ $position->created_at }} -- Updated at: {{ $position->updated_at }}</p>
    <p class="font-weight-light"></p>

    @if ($position->id != 0)
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" onclick="createEmployee()">
            Create
        </button>
    @endif

    <p class="mt-3"><strong>Job Description: </strong>{{ $position->job_description }}</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Employees in position:  {{ $position->name }} (Total: <span id="employeesCount">{{ $position->employees_count}}</span> employees)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Term</th>
                            @if ($position->id == 0)
                                 <th>Position</th>
                            @endif
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
                            <th>Term</th>
                            @if ($position->id == 0)
                                 <th>Position</th>
                            @endif
                            <th>Description</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($position->employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>
                                    <img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="{{ $employee->file_path }}" class="rounded" alt="{{$employee->name}} image">
                                </td>
                                @if (isset($employee->custom_term))
                                    <td>{{ $employee->custom_term }}</td>
                                @else
                                <td><a href="{{route('admin.terms.show', $employee->term_id)}}">{{ $employee->term->name }} ({{ $employee->term->year_start }}-{{ $employee->term->year_end }})</a></td>
                                @endif

                                @if (isset($employee->custom_position))
                                    <td>{{ $employee->custom_position }}</td>

                                @endif
                                <td>{{ $employee->description }}</td>
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
