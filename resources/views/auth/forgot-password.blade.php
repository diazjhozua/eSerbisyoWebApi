@extends('layouts.auth')

@section('content')
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row ">
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
                                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                    <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                        and we'll send you a link to reset your password!</p>
                                </div>

                                @include('inc.message')

                                <form class="user" method="post" action="{{ route('forget.password.post') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control form-control-user"
                                            id="exampleInputEmail" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address...">
                                    </div>

                                    <div class="form-group"><button class="btn btn-primary btn-user btn-block" type="submit">Reset Password</button></div>
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
