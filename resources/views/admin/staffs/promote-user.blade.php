@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/admin/staffs/staff-user.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', $title)

@section('content')

    @include('admin.staffs.change-role')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">

            @if (Auth::user()->user_role_id == 1)
                <h6 class="m-0 font-weight-bold text-primary">Total: <span id="userCount">{{ $users->count()}}</span></h6>
            @else
                <h6 class="m-0 font-weight-bold text-primary">Total: <span id="userCount">{{ $users->count()}}</span> (Users) ---- <span id="staffCount">{{ $staffCount }}</span> (Admin Staff)</h6>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th>Verification Status</th>
                            <th>Account Status</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th>Verification Status</th>
                            <th>Account Status</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                        <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                        src="{{ isset($user->file_path) ? $user->file_path :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$user->getFullNameAttribute()}} image">
                                </td>
                                <td>{{$user->getFullNameAttribute()}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->user_role->role}}</td>
                                <td>{{$user->status == 'Enable' ? 'Enable Access' : 'Restricted Access'}}</td>
                                <td>{{$user->is_verified == 1 ? 'Verified' : 'Unverified' }}</td>

                                <td>{{$user->created_at}}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-item mb-1">
                                            @if (Auth::user()->user_role_id == 1)
                                                <button class="btn btn-success btn-sm btnPromote" onclick="promoteAnyUser({{$user->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Disable User">
                                                    <span class="btnText btnPromoteTxt">Promote</span>
                                                    <i class="btnPromoteIcon fas fa-level-up-alt ml-1"></i>
                                                    <i class="btnPromoteLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                                </button>
                                            @else
                                                <button class="btn btn-success btn-sm btnPromote" onclick="promote({{$user->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Disable User">
                                                    <span class="btnText btnPromoteTxt">Promote</span>
                                                    <i class="btnPromoteIcon fas fa-level-up-alt ml-1"></i>
                                                    <i class="btnPromoteLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                                </button>
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No information admin user yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
