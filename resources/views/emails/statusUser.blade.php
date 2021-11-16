@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

@section('messageUpper')
    @if ($request['status'] == 'Enable')
        Your account has been enabled, you can now login your account using your credentials.
        Please comply to the rules and regulation to prevent your from being banned.
    @else
        Your account has been disabled, please go to the barangay center to be able make your account enable again.
        The administration think that you didn't comply with the rules and regulation in using the android application.
    @endif

@endsection

{{-- Lower Message --}}
@section('messageLower1')
    @if ($request['status'] == 'Enable')
        Note: {{ $request['admin_status_message'] }}
    @else
        Reason: {{ $request['admin_status_message'] }}
    @endif
@endsection

