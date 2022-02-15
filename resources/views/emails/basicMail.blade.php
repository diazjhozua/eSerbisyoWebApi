@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper')
    {!! $message !!}
@endsection

{{-- Lower Message --}}
@section('messageLower1')
    If you have any problem with this transaction, please report it to the eSerbisyo application or go to the barangay Cupang office. Thankyou!
@endsection

