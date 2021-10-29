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
    <script src="{{ asset('js/admin/users/index.js')}}"></script>

    {{-- Date Picker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Users')

@section('content')

    {{-- Included Modals --}}
    {{-- @include('admin.users.form') --}}

    {{-- Delete Modal Confirmation --}}
    @include('admin.users.status-form')
    @include('admin.users.request-form')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
    </div>

    <div class="row">
        {{-- Restricted User registered this day --}}

        <div class="col-sm mt-2">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Restricted User (Blocked)
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="restrictedCount">{{$usersData->blocked_user_count}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- User Verification Request --}}

        <!-- Verification Requests Card -->
        <div class="col-sm mt-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Verification Requests
                                </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="verificationCount">{{ $verificationCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="far fa-id-card fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- User registered this day --}}
        <div class="col-sm mt-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" >
                                User registered this day</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="thisDayCount">{{ $usersData->this_day_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- User registered this month --}}
        <div class="col-sm mt-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="thisMonthCount">
                                User registered this month</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersData->this_month_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- User registered this year Card --}}
        <div class="col-sm mt-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" id="thisYearCount">
                                User registered this year</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersData->this_year_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3">These are the list of users that are available for the residents to view. If the user's account status is Disabled, the specified user cannot publish feedbacks, like and comment announcement, file a complaint, file a missing report, and any function that involves sensitive information.</p>

    <!-- DataTales Example -->
    <div class="card shadow mt-2 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List (Total: <span id="usersCount">{{ $users->count() }}</span>)</h6>
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
                                    src="{{ isset($user->file_path) ? asset('storage/'.$user->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}" class="rounded" alt="{{$user->getFullNameAttribute()}} image">
                                </td>
                                <td>{{$user->getFullNameAttribute()}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->user_role->role}}</td>
                                <td>{{$user->status == 'Enable' ? 'Enable Access' : 'Restricted Access'}}</td>
                                <td>{{$user->is_verified == 1 ? 'Verified' : 'Unverified' }}</td>

                                <td>{{$user->created_at}}</td>
                                <td>
                                    <ul class="list-inline m-0">
                                        {{-- Check if there is  --}}
                                        @if ($user->is_verified != 1)
                                            @if (isset($user->latest_user_verification))
                                                @if ($user->latest_user_verification->status == 'Pending')
                                                    <li class="list-item mb-1">
                                                        <button class="btn btn-info btn-sm" type="button" onclick="viewVerificationRequest({{ $user->latest_user_verification->id }})" data-toggle="tooltip" data-placement="top" title="Review Request">
                                                            <span class="text-left">Review Request</span>
                                                            <i class="fas fa-search ml-1"></i>
                                                        </button>
                                                    </li>
                                                @endif
                                            @endif
                                        @endif

                                        {{-- Check if the user is enabled or disabled --}}
                                        @if ($user->status == 'Enable')
                                            <li class="list-item mb-1">
                                                <button class="btn btn-danger btn-sm" onclick="disable({{$user->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Disable User">
                                                    <span class="btnText">Restrict</span>
                                                    <i class="fas fa-ban ml-1"></i>
                                                </button>
                                            </li>
                                        @else
                                            <li class="list-item mb-1">
                                                <button class="btn btn-success btn-sm" onclick="enable({{$user->id}})" type="button" data-toggle="tooltip" data-placement="top" title="Enable User">
                                                    <span class="btnText">Enable</span>
                                                    <i class="fas fa-check-circle ml-1"></i>
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <p>No registered users yet</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection






