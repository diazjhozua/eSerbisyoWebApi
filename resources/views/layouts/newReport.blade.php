<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('assets/img/favicon.ico') }}"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="{{ asset('admin/css/report.css')}}" rel="stylesheet">
    </head>

    <body>
        <div class="book">
            <div class="page">
                <button id="printPageButton"  onclick="window.print()" class="btn btn-primary btn-lg btn-block">Print Report</button>
                <div class="subpage">
                    <div class = brg>
                        <b> Barangay Cupang </b>
                        <br>
                        Muntinlupa City
                        <br>
                        Philippines, NCR
                        <br>
                        P: 0960 850 53957
                        <br>
                        E: cupang.muntinlupacity@gmail.com
                    </div>

                    <div class="cupang">
                        <img src="{{ asset('assets/img/cupang.png') }}" alt="hehehe" height="100" width="100">
                    </div>

                    <div class = "lindot">
                        <hr>
                    </div>

                    @yield('content')

                    <hr class="mt-2" style="height:2px;background-color: black; ">
                    <p style="text-align: center; font-weight:bold;">

                        Report ends here
                    </p>
                    <hr style="height:2px;background-color: black; ">
                </div>
            </div>
        </div>
    </body>
</html>
