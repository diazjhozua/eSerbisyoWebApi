<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>

        <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}"/>
        @yield('page-css')
    </head>
    <body>
        @yield('content')
    </body>
</html>
