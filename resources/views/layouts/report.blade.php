<!DOCTYPE html>
<html lang="en">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="robots" content="noindex">

            {{-- Title --}}
            <title> @yield('title') </title>

            {{-- @include('inc.bootstrap3') --}}

            <style>
                body {
                    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif
                }
                .text-right {
                    text-align: right;
                }

                .textbox {
                    display:flex;
                    height: 100px;
                }

                .alignleft {
                    text-align:left;
                }

                .alignright {
                    text-align:right;
                }

                .reportTitle {
                    text-align: center;
                    font-size: 20px;
                    margin-bottom: 20px;
                }
                table
                {
                    page-break-before: avoid;
                }

                .row:after {
                    content: "";
                    display: table;
                    clear: both;
                }
                .text-center {
                    text-align: center;
                }

                .row {
                    margin-right: -15px;
                    margin-left: -15px;
                }
                .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
                    position: relative;
                    min-height: 1px;
                    padding-right: 15px;
                    padding-left: 15px;
                }
                .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
                    float: left;
                }
                .col-xs-12 {
                    width: 100%;
                }
                .col-xs-11 {
                width: 91.66666667%;
                }
                .col-xs-10 {
                width: 83.33333333%;
                }
                .col-xs-9 {
                width: 75%;
                }
                .col-xs-8 {
                width: 66.66666667%;
                }
                .col-xs-7 {
                width: 58.33333333%;
                }
                .col-xs-6 {
                width: 50%;
                }
                .col-xs-5 {
                width: 41.66666667%;
                }
                .col-xs-4 {
                width: 33.33333333%;
                }
                .col-xs-3 {
                width: 25%;
                }
                .col-xs-2 {
                width: 16.66666667%;
                }
                .col-xs-1 {
                width: 8.33333333%;
                }

                table {
                border-spacing: 0;
                border-collapse: collapse;
                }

                th {
                    text-align: left;
                }

                .well {
                    min-height: 20px;
                    padding: 19px;
                    margin-bottom: 20px;
                    background-color: #f5f5f5;
                    border: 1px solid #e3e3e3;
                    border-radius: 4px;
                    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
                            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
                }

                .tableContent {
                    font-size: 15px;
                    width: 100%;
                    margin: auto;
                    border: 1px solid black;
                    page-break-before: always;
                }

                .tableContent th {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid black;
                }

                .tableContent td {
                    padding: 10px;
                    text-align: left;
                    border: 1px solid black;
                }


                .breakBefore {
                    page-break-before: always;
                }

                .tableLayout {
                    font-size: 15px;
                    width: 100%;
                    margin: auto;
                    border: 1px solid black;

                }

                .tableLayout th {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid black;
                }

                .tableLayout td {
                    padding: 10px;
                    text-align: left;
                    border: 1px solid black;
                }

            </style>
    </head>

    <body style="background: white">
        {{-- Header --}}
        <div class="textbox">
                <p class="alignleft">
                    <strong>Barangay Cupang</strong><br>
                    Muntinlupa City<br>
                    Philippines, NCR<br>
                    P: 0960 850 53957 <br>
                    E: cupang.muntinlupacity@gmail.com <br>
                    <br>
                </p>

                <p class="alignright">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/cupang.png'))) }}"
                    width="110px" height="100px"
                    alt="logo">
                </p>
        </div>
        <div style="margin-bottom: 0px">&nbsp;</div>
        <hr>
        {{-- End of header --}}

        {{-- Report Content --}}
        @yield('content')

        <div style="margin-bottom: 0px">&nbsp;</div>

        {{-- Footer --}}
        <hr>
        <div style="text-align: center;">
            <small class="text-justify"><strong>Report ends here</strong></small>
        </div>
        <hr>
        {{-- End of footer --}}

    </body>
</html>
