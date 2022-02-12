@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')
    Your submitted feedback <strong>(ID - {{ $feedback->id }})</strong> was noted by the barangay official. Thank you for your opinion and being a
    responsible resident in the barangay Cupang!
@endsection

{{-- Lower Message --}}
@section('messageLower1')
    Admin Message: {{ $feedback->admin_respond }}
@endsection



