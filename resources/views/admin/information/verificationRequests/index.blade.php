@extends('layouts.admin')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="{{ asset('js/admin/information/verificationRequests/index.js')}}"></script>
@endsection

{{-- Title Page --}}
@section('title', 'User Verifications')

@section('content')

    @include('admin.information.verificationRequests.requestFormModal')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Latest Verification Requests
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <p class="text-justify">
        These are the following verification requests submitted by the residents in the e-Serbisyo android application. Residents must get their account verified to access the following functions: Like and Comment Announcements,
        Comment on Missing Person or Item report, submit feedbacks and reports, and requests certificates.
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="usersCount">{{ $userVerifications->count() }}</span>)</h6>
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
                            <th>Application Status</th>
                            <th>Admin Message</th>
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
                            <th>Application Status</th>
                            <th>Admin Message</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse ($userVerifications as $userVerification)
                            <tr>
                                <td>
                                    <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%"
                                    src="{{ isset($userVerification->user->file_path) ? asset('storage/'.$userVerification->user->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$userVerification->user->getFullNameAttribute()}}">
                                </td>
                                <td>{{$userVerification->user->getFullNameAttribute()}}</td>
                                <td>{{$userVerification->user->email}}</td>
                                <td>{{$userVerification->user->user_role->role}}</td>
                                <td>{{$userVerification->status}}</td>
                                <td>{{ $userVerification->admin_message != null ? $userVerification->admin_message : 'Not yet responded'}}</td>
                                <td>{{$userVerification->user->created_at}}</td>
                                <td>
                                    @if ($userVerification->status == 'Pending')
                                        <ul class="list-inline m-0">
                                            <li class="list-item mb-1">
                                                <button class="btn btn-info btn-sm" type="button" onclick="viewVerificationRequest({{ $userVerification->id }})" data-toggle="tooltip" data-placement="top" title="Review Request">
                                                    <span class="btnText btnVerifyTxt">Review Request</span>
                                                    <i class="btnVerifyIcon fas fa-money-check ml-1"></i>
                                                    <i class="btnVerifyLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                                </button>
                                            </li>
                                        </ul>
                                    @else
                                        <ul class="list-inline m-0">
                                            <li class="list-item mb-1">
                                                <button class="btn btn-info btn-sm" type="button" onclick="viewVerificationRequest({{ $userVerification->id }})" data-toggle="tooltip" data-placement="top" title="Review Request" disabled>
                                                    <span class="btnText btnVerifyTxt">Review Request</span>
                                                    <i class="btnVerifyIcon fas fa-money-check ml-1"></i>
                                                    <i class="btnVerifyLoadingIcon fa fa-spinner fa-spin" hidden></i>
                                                </button>
                                            </li>
                                        </ul>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <p>No verifications submitted yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

