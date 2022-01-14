@extends('layouts.admin')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/certification/requirements/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Requirements')


@section('content')
    {{-- Included Modals --}}

    {{-- Create/Edit --}}
    @include('admin.certification.requirements.formModal')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Requirements
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <p class="text-justify">These are the list of requirements. To add that specific requirement to a specific certificate, please go to the profile of that certificate</a>.
    </p>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createRequirement()">
        Create
    </button>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="requirementsCount">{{ $requirements->count() }}</span>)</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Certificate with this req count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Certificate with this req count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>

                    <tbody>
                        @forelse ($requirements as $requirement)
                            <tr>
                                <td>{{ $requirement->id }}</td>
                                <td>{{ $requirement->name }}</td>
                                <td>{{ $requirement->certificates_count  }}</td>
                                <td>{{ $requirement->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm editBtn" onclick="editRequirement({{ $requirement->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm deleteBtn" type="button" onclick="deleteRequirement({{ $requirement->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No requirements created</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

