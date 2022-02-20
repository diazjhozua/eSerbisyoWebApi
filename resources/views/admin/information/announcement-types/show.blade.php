@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/information/announcement-types/show.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', $type->name)

@section('content')

    {{-- Included Modals --}}
    @include('admin.information.announcements.formModal')

    {{-- Report Route to the modal --}}
    <!-- section('reportRoute', route('admin.announcement-types.report.show', $type->id)) -->
    @include('admin.information.announcements.reportSelectModal')

    {{-- Delete Modal Confirmation --}}
    @include('inc.delete')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Type: {{ $type->name }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        @if (Auth::user()->user_role_id < 5)
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#reportModal"><i
                class="fas fa-download fa-sm text-white-50" ></i> Download Report</button>
        @endif
    </div>

    <p class="font-weight-light">Created at: {{ $type->created_at }} -- Updated at: {{ $type->updated_at }}</p>
    <p class="font-weight-light"></p>
    <input type="hidden" id="type_id" value="{{$type->id}}"/>
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
                            <th>ID</th>
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
                            <th>ID</th>
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
                                <td>{{ $announcement->id }}</td>
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
