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
    <script src="{{ asset('js/admin/users/profile.js')}}"></script>
@endsection


{{-- Title Page --}}
@section('title', 'Profile')

@section('content')

    {{-- Included Modals --}}
    @include('admin.users.edit-user-form')

    {{-- Delete Modal Confirmation --}}
    {{-- @include('admin.users.status-form')
    @include('admin.users.request-form') --}}

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><button class="btn btn-primary" onclick="window.location=document.referrer;" type="submit"><i class="fas fa-caret-square-left"></i></button> Your Profile
            <a class="btn " onclick="window.location.reload();"> <i class="fas fa-sync"></i></a>
        </h1>
    </div>

    <div class="container userProfile mb-3 ">
        <div class="row">
            <div class="col-md-5">
                <div class="card p-4 bg-light">
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-profile rounded-circle m-0 text-center" id="userProfileImg" width="200px" height="200px"
                                src="{{ isset(Auth::user()->file_path) ? asset('storage/'.Auth::user()->file_path) :  'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'}}"
                                class="rounded" alt="{{Auth::user()->first_name}}  {{Auth::user()->last_name}} image">
                        </div>
                </div>
                <div class="mt-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <button type="button" class="btn btn-primary btn-sm btn-block mt-2" onclick="editProfile()">Edit</button>
                            </div>
                            <div class="col-sm">
                                <button type="button" class="btn btn-warning btn-sm btn-block mt-2" onclick="changePassword()">Change Passsword</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="col-md-7 mt-2">
                <div class="card">
                    <h5 class="card-header">Details</h5>
                    <div class="card-body d-flex justify-content-center">
                        <div class="row mt-4">
                            <div class="col-sm-4 h6">First Name:</div>
                            <div class="col-sm-8 h6 text-black"><strong id="userFirstName">{{$user->first_name}}</strong></div>
                            <div class="col-sm-4 h6">Middle Name:</div>
                            <div class="col-sm-8 h6 text-black"><strong id="userMiddleName">{{ isset($user->middle_name) ? $user->middle_name : 'n/a'}}</strong></div>
                            <div class="col-sm-4 h6">Last Name:</div>
                            <div class="col-sm-8 h6 text-black"><strong id="userLastName">{{$user->last_name}}</strong></div>
                            <div class="col-sm-4 h6">Role:</div>
                            <div class="col-sm-8 h6 text-black"><strong  id="userRole">{{$user->user_role->role}}</strong></div>
                            <div class="col-sm-4 h6">Address:</div>
                            <div class="col-sm-8 h6 text-black"><strong id="userAddress">{{$user->address}}</strong></div>
                            <div class="col-sm-4 h6">Registered Date: </div>
                            <div class="col-sm-8 h6 text-black" id="userCreatedAt">{{$user->created_at}}</div>
                            <div class="col-sm-4 h6">Last Update: </div>
                            <div class="col-sm-8 h6 text-black" id="userUpdatedAt">{{$user->updated_at}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection






