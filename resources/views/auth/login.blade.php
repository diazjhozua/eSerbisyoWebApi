@extends('layouts.auth')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/login.js')}}"></script>
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
                            <div class="p-4">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>

                                <div class="alert alert-info mb-4" style="font-size: 15px" role="alert">
                                    Note: This is for admin login only. If you are a resident or biker, please download the android application
                                    to access the system.
                                </div>

                                <form name="loginForm" id="loginForm" class="user" method="POST" action="{{ route('login.authenticate') }}" novalidate>

                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                            id="exampleInputEmail"
                                            placeholder="Enter Email Address...">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                                Me</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block btnFormSbmit">
                                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                                        <span class="btnTxt">Log In</span>
                                    </button>

                                </form>
                                <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('forget.password.get') }}">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register.get') }}">Create an Account!</a>
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
