@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/androids/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'Androids')

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.androids.formModal')

    @include('inc.delete')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Android apk links
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" onclick="createAndroid()">
        Create
    </button>

    <p class="mt-3">These are the list of android apk list that resident will download to use the application</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="androidsCount">{{ $androids->count() }}</span>)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>URL</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>URL</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($androids as $android)
                            <tr>
                                <td>{{ $android->id }}</td>
                                <td>{{ $android->version }}</td>
                                <td>{{ $android->description }}</td>
                                <td><a href="{{ $android->url }}" target="_blank">{{ $android->url}}</a></td>
                                <td>{{ $android->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-primary btn-sm" onclick="editAndroid({{ $android->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteAndroid({{ $android->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No android version yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection






