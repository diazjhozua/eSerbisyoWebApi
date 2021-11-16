@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

@section('messageUpper')
    @if ($request->status == 'Approved')
        Your verification request is approved, you may now used this account for submitting feedbacks, communicate in posts,
        reporting, and certificate request.
    @else
        Your verification request is denied. The administrator think that the credential you sent is not enough to verified your account.
        You may submit another verification request.
    @endif

@endsection

{{-- Lower Message --}}
@section('messageLower1')
    @if ($request->status == 'Approved')
        Note: {{ $request->admin_message }}
    @else
        Reason: {{ $request->admin_message }}
    @endif
@endsection


