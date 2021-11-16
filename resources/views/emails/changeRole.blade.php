@extends('layouts.email')

{{-- Title --}}
@section('greetings', 'Hello!')


@if ($subject == 'User Promotion')
    {{-- If user promoted --}}

    {{-- Upper Message --}}
    @section('messageUpper')
        Your account has been promoted to {{ $userRole->role }}. You are now considered part of an administration team and you can
        access now the dashboard in the website. Click this button to login your credentials.
    @endsection

    @section('button')
        {{-- URL LINK --}}
            <a href="{{ route('login') }}" class="button button-primary" target="_blank" rel="noopener" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">
            {{-- BUTTON TXT --}}
            Login</a>
    @endsection

    {{-- Footer Message --}}
    @section('messageFooter')
        If you're having trouble clicking the "Login" button, copy and paste the URL below into your web browser:
        <span class="break-all" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; word-break: break-all;">
            <a href="{{ route('login') }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4;">
            {{ route('login') }}</a>
        </span>
    @endsection

    {{-- end if --}}
@else
    {{-- If user demoted --}}

    {{-- Upper Message --}}
    @section('messageUpper')
        Your account has been demoted to {{ $userRole->role }}. You are now not allowed to
        access now the dashboard in the website. Please go to barangay office if there is a misunderstanding.
        You can still use the android application if you want to use it as a resident.
    @endsection

    {{-- Lower Message --}}
    @section('messageLower1', 'You can still use the android application if you want to use it as a resident')

    {{-- end if --}}
@endif



