@extends ('layouts.user')


@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/register.js')}}"></script>
@endsection


@section('content')

    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Sign Up</h1>
                   <p>Fill out the form below to sign up for Tivo. Already signed up? Then just <a class="white" href="log-in.html">Log In</a></p>
                    <!-- Sign Up Form -->
                    <div class="form-container">

                        <div id="successMessage" class="alert alert-info mb-3 text-justify" style="font-size: 15px" role="alert">
                            Note: Any user can register to this page. If you are a resident or biker, use the android application
                            to access the system after successfull registration. For administrator, please wait for the other admin to promote your priviledges.
                            You will recieved an email if your account has been granted an administration access.
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
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->
@endsection
