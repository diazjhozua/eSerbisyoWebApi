@extends('layouts.information')

@section('page-css')
    <!-- Custom styles for type template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('page-js')
    <!-- Page level plugins -->
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('admin/js/demo/datatables-demo.js')}}"></script>

    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/announcement-types/show.js')}}"></script>

    {{-- Date Picker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
@endsection

{{-- Title Page --}}
@section('title', $type->name)

@section('content')

    {{-- Included Modals --}}
    @include('admin.announcements.form')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Type: {{ $type->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>

    <p class="font-weight-light">Created at: {{ $type->created_at }} -- Updated at: {{ $type->updated_at }}</p>
    <p class="font-weight-light"></p>

    @if ($type->id != 0)
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" onclick="createAnnouncement()">
            Create
        </button>
    @endif

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Announcements about {{ $type->name }} (Total: <span id="announcementsCount">{{ $type->announcements_count}}</span> announcements)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
                            <th>Pictures Count</th>
                            <th>Likes Count</th>
                            <th>Comments Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Title</th>
                            @if ($type->id == 0)
                                <th>Custom-Type</th>
                            @endif
                            <th>Pictures Count</th>
                            <th>Likes Count</th>
                            <th>Comments Count</th>
                            <th>Date Modified</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($type->announcements as $announcement)
                            <tr>
                                <td>{{ $announcement->title }}</td>
                                @if (isset($announcement->custom_type))
                                    <td>{{ $announcement->custom_type }}</td>
                                @endif
                                <td>{{ $announcement->announcement_pictures_count}}</td>
                                <td>{{ $announcement->likes_count}}</td>
                                <td>{{ $announcement->comments_count}}</td>
                                <td>{{ $announcement->updated_at }}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item mb-1">
                                            <a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('admin.announcements.show', $announcement->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mb-1">
                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteAnnouncement({{ $announcement->id }})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </td>
                            </tr>

                        @empty
                            <p>No announcements yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
