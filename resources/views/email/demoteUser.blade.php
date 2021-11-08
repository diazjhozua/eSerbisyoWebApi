@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')
    Your account has been demoted to {{ $userRole->role }}. You are now not allowed to
    access now the dashboard in the website. Please go to barangay office if there is a misunderstanding.
    You can still use the android application if you want to use it as a resident.
@endsection



