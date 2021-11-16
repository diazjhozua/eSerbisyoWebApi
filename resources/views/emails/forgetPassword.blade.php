@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')

{{-- Upper Message --}}
@section('messageUpper', 'You are receiving this email because we received a password reset request for your account.')

@section('button')
    {{-- URL LINK --}}
        <a href="{{ route('reset.password.get', $token) }}" class="button button-primary" target="_blank" rel="noopener" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">
        {{-- BUTTON TXT --}}
        Reset Password</a>
@endsection

{{-- Lower Message --}}
@section('messageLower1', 'This password reset link will expire in 60 minutes.')
@section('messageLower2', 'If you did not request a password reset, no further action is required.')

{{-- Footer Message --}}
@section('messageFooter')
    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
    <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; word-break: break-all;">
        <a href="{{ route('reset.password.get', $token) }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4;">
        {{ route('reset.password.get', $token) }}</a>
    </span>
@endsection

