@extends ('layouts.user')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/login.js')}}"></script>
@endsection


@section('content')

    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Log In</h1>
                   <p>You don't have a password? Then please <a class="white" href="{{ route('register.get') }}">Sign Up</a></p>
                    <!-- Sign Up Form -->
                    <div class="form-container">
                        <div class="alert alert-info mb-4 text-left" style="font-size: 15px" role="alert">
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
                                <button type="submit" class="btn btn-primary btn-block btnFormSbmit">
                                    <i class="loadingIcon fa fa-spinner fa-spin" hidden></i>
                                    <span class="btnTxt">Log In</span>
                                </button>

                            </form>

                            <hr/>
                            <div class="text-center">
                                <a class="small" href="{{ route('forget.password.get') }}">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('register.get') }}">Create an Account!</a>
                            </div>
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->
@endsection
