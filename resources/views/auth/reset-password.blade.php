@extends ('layouts.user')

@section('page-js')
    {{-- Custom Scripts for this blade --}}
    <script src="{{ asset('js/auth/reset.js')}}"></script>
@endsection


@section('content')
    <!-- Header -->
    <header id="header" class="ex-2-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Reset Password</h1>
                    <!-- Reset Password Form -->
                    <div class="form-container">

                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4"></h1>
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
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->
@endsection
