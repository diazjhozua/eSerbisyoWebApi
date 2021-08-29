@extends('template')

@section('css')
    <link href="{{ asset('css/auth/login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="login-clean">

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <h2>Forgot Password</h2>
            <div class="illustration"><i class="fas fa-school"></i></div>
            @error('email')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @enderror
            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="Email" value="{{ $request->email }}"></div>

            @error('password')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>{{ $message }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @enderror
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="New Password"></div>
            <div class="form-group"><input class="form-control" type="password" name="password_confirmation" placeholder="New Password (repeat)"></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Update</button></div><a class="forgot" href="#">Don't have an account? Click here to register</a>
        </form>
    </section>
@endsection
