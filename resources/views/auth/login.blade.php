@extends('auth.layouts.app')

@section('css')

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
                        <div class="col-lg-5 d-none d-lg-block p-4">
                            <img src="https://scontent.fmnl4-1.fna.fbcdn.net/v/t39.30808-6/243218478_227203076120895_4263312640655818418_n.jpg?_nc_cat=104&ccb=1-5&_nc_sid=09cbfe&_nc_eui2=AeF05x8f7ZdOqwMG5rn5YOiyKqWVRP972EIqpZVE_3vYQgxTjMB9IGW_Cn42cLIX79w_TLKbJI-rIZP014EWcP7S&_nc_ohc=r3J2oUW6fnYAX8NcsIJ&_nc_ht=scontent.fmnl4-1.fna&oh=be06cb32935c05169559cfd19d0e8f84&oe=617EE865"
                                class="img-fluid mx-auto">
                        </div>
                        <div class="col-lg-7">
                            <div class="p-4">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                @include('inc.message')

                                <form class="user" method="post" action="{{ route('login.authenticate') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                                id="exampleInputEmail"
                                                placeholder="Enter Email Address...">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                                Me</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Log In</button>
                                    <hr>

                                    <div class="alert alert-info m-0" style="font-size: 10px" role="alert">
                                        Note: This is for admin login only. If you are a resident or biker, please download the android application
                                        to access the system.
                                    </div>
                                </form>
                                <hr>
                                <div class="text-center">
                                    {{-- <a class="small" href="{{ route('password.request')}}">Forgot Password?</a> --}}
                                </div>
                                <div class="text-center">
                                    {{-- <a class="small" href="{{ route('register') }}">Create an Account!</a> --}}
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
