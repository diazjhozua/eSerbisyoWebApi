@extends('layouts.auth')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/reset.js')}}"></script>
@endsection


@section('content')

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block jumbotron vertical-center" style="background:transparent !important; padding-right:20px; border-right: 1px solid #ccc;">
                            <div class="p-3">
                                <img
                                src="{{ asset('assets/img/cupang.png') }}"
                                alt="Barangay Cupang Logo"
                                class="img-fluid mx-auto">
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Reset Password</h1>
                                </div>

                                <div id="successMessage" class="alert alert-success alert-dismissible fade show" style="font-size: 15px" role="alert" hidden>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form name="resetPassForm" id="resetPassForm" class="user" method="post" action="{{ route('reset.password.post') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group">
                                        <input type="email" id="email" class="form-control form-control-user" name="email"
                                            placeholder="Enter your email"
                                            value="">
                                    </div>

                                    <div class="form-group">
                                        <input type="password" id="password" name="password" class="form-control form-control-user"
                                            minlength="8"
                                            maxlength="16"
                                            autocomplete="off"
                                            id="exampleInputPassword" placeholder="New Password">
                                    </div>

                                    <div class="form-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-user"
                                            minlength="8"
                                            maxlength="16"
                                            autocomplete="off"
                                            id="exampleInputPassword" placeholder="Confirm Password">
                                    </div>


                                    <button type="submit" class="btn btn-primary btn-block btnFormSbmit">
                                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                                        <span class="btnTxt">Reset</span>
                                    </button>
                                    <hr>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register.get') }}">Create an Account!</a>
                                </div>

                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
