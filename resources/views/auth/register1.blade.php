@extends('layouts.auth')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/register.js')}}"></script>
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
                                    <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                </div>

                                <div id="successMessage" class="alert alert-info mb-3 text-justify" style="font-size: 15px" role="alert">
                                    Note: Any user can register to this page. If you are a resident or biker, use the android application
                                    to access the system after successfull registration. For administrator, please wait for the other admin to promote your priviledges.
                                    You will recieved
                                    an email if your account has been granted an administration access.
                                </div>

                                <form name="registerForm" id="registerForm" class="user" method="post" action="{{ route('register.post') }}" novalidate>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-sm mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user"
                                                id="first_name"
                                                name="first_name"
                                                placeholder="First Name">
                                        </div>
                                        <div class="col-sm">
                                            <input type="text" class="form-control form-control-user"
                                                id="middle_name"
                                                name="middle_name"
                                                placeholder="Middle Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="last_name"
                                            id="last_name"
                                            placeholder="Last name">
                                    </div>


                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                            name="email"
                                            id="email"
                                            placeholder="Email Address">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" name="password"
                                                id="password" placeholder="Password" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user"
                                                name="password_confirmation"
                                                id="password_confirmation" placeholder="Repeat Password" autocomplete="off">
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block btnFormSbmit">
                                        <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                                        <span class="btnTxt">Register Account</span>
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('forget.password.get')}}">Forgot Password?</a>
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

{{-- <div class="container">
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
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>

                        <div class="alert alert-info mb-3 text-justify" style="font-size: 15px" role="alert">
                            Note: Any user can register to this page. If you are a resident or biker, use the android application
                            to access the system after successfull registration. For administrator, please wait for the other admin to promote your priviledges.
                            You will recieved an email if your account has been granted an administration access.
                        </div>

                        <form id="registerForm" id="registerForm" class="user" method="post" action="{{ route('register.post') }}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">

                                    <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                        name="bame"
                                        placeholder="First Name">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="exampleLastName"
                                        placeholder="Wala pa ito Name">
                                </div>
                            </div>
                            <div class="form-group">
                                @error('email')
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                                <input type="email" class="form-control form-control-user" name="email" id="exampleInputEmail"
                                    placeholder="Email Address">
                            </div>
                            <div class="form-group row">
                                @error('password')
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" name="password"
                                        id="exampleInputPassword" placeholder="Password">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" name="password_confirmation"
                                        id="exampleRepeatPassword" placeholder="Repeat Password">
                                </div>
                            </div>
                            <button class="btn btn-primary  btn-user btn-block" type="submit">Register Account</button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('forget.password.get')}}">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> --}}

@endsection
