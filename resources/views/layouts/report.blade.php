<!DOCTYPE html>
<html lang="en">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="robots" content="noindex">

            {{-- Title --}}
            <title> @yield('title') </title>

            @include('inc.bootstrap3')

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
