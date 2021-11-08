@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/positions/index.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Positions')

@section('content')
    {{-- Included Modals --}}

    {{-- Create/Edit --}}
    @include('admin.information.positions.form')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Positions
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>

    <p class="text-justify">
        Position can be a way to determine the position of the barangay officials/employees in different terms.
        The position no. dictates which parangay position is the highest (Which No. 1 makes the highest out of other positions).
        The employee count show how many officials are elected in that position based on the database.
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createPosition()">
        Create
    </button>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="positionCount">{{ $positions->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position Rank</th>
                            <th>Description</th>
                            <th>Employee Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position Rank</th>
                            <th>Description</th>
                            <th>Employee Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($positions as $position)
                            <tr>
                                <td>{{ $position->id }}</td>
                                <td>{{ $position->name }}</td>
                                <td>{{ $position->ranking }}</td>
                                <td>{{ $position->job_description }}</td>
                                <td>{{ $position->employees_count}}</td>
                                <td>{{ $position->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" position="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.positions.show', $position->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        @if ($position->id !== 0)
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-primary btn-sm" onclick="editPosition({{ $position->id}})" position="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item mb-1">
                                                <button class="btn btn-danger btn-sm" position="button" onclick="deletePosition({{ $position->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </li>
                                        @endif

                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No positions created</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection





