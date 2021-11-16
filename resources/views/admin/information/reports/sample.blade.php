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
                    margin: auto;
                    border: 1px solid black;
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
        <div class="row">
            <div class="col-xs-6">
                <h4>Feedbacks Report</h4>
                <address>
                    <span><strong>Generated By: </strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} (#{{ Auth::id() }})</span><br>
                    <span><strong>Position: </strong>{{ Auth::user()->user_role->role }} </span><br>
                    <span><strong>Date Generated: </strong>{{ date('Y-m-d H:i:s') }}</span><br>
                    <hr>
                        <h4 class="text-center"><strong>Overall Statistics</strong></h4>
                        <span><strong>Positive: </strong>40 (30%)</span><br>
                        <span><strong>Neutral: </strong></span><br>
                        <span><strong>Negative: </strong></span><br>
                    {{-- @if ($request->polarity_option == 'all')
                        <h5 class="text-center"><strong>Overall Statistics</strong></h5>
                        <span><strong>Positive: </strong>40 (30%)</span><br>
                        <span><strong>Neutral: </strong></span><br>
                        <span><strong>Negative: </strong></span><br>
                    @endif --}}
                </address>

            </div>

            <div class="col-xs-5">
                <h4 class="text-center"><strong>Timeframe</strong></h4>
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <th>Date Start: </th>
                            <td class="text-right">{{ date('Y-m-d H:i:s') }}</td>
                        </tr>

                        <tr>
                            <th>Date End: </th>
                            <td class="text-right">{{ date('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Sort By: </th>
                            <td class="text-right">id (ASC)</td>
                        </tr>
                        <tr>
                            <th>Polarity filter: </th>
                            <td class="text-right">all</td>
                        </tr>

                        <tr>
                            <th>Status filter: </th>
                            <td class="text-right">all</td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <table style="width: 100%">
                    <tbody>
                            <tr>
                                <th>Pending Count: </th>
                                <td class="text-right"></td>
                            </tr>

                            <tr>
                                <th>Noted Count: </th>
                                <td class="text-right"></td>
                            </tr>

                            <tr>
                                <th>Ignored Count: </th>
                                <td class="text-right"></td>
                            </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 0px">&nbsp;</div>

                <table style="width: 100%; margin-bottom: 20px;">
                    <tbody>

                        <tr class="well" style="padding: 5px">
                            <th style="padding: 5px"><div> Feedback Received: </div></th>
                            <td style="padding: 5px" class="text-right"><strong> 32</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <table class="tableContent">
                <thead style="background: #F5F5F5;">
                    <tr>
                        <th>ID</th>
                        <th>Submitted by</th>
                        <th>Feedback Type</th>
                        <th>Polarity</th>
                        <th>Message</th>
                        <th>Admin Respond</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                        <th>Date Updated</th>
                    </tr>
                </thead>
            <tbody>
                  <tr>
                    <td>2</td>
                    <td>Jhozua Diaz</td>
                    <td>Disaster Customer</td>
                    <td>Negative</td>
                    <td>Tang ina  </td>
                    <td>Okay po sir</td>

                    <td>Pending</td>
                    <td>{{ date('Y-m-d H:i:s') }}</td>
                    <td>{{ date('Y-m-d H:i:s') }}</td>
                </tr>

                                  <tr>
                    <td>2</td>
                    <td>Jhozua Diaz</td>
                    <td>Disaster Customer</td>
                    <td>Negative</td>
                    <td>Tang ina  </td>
                    <td>Okay po sir</td>

                    <td>Pending</td>
                    <td>{{ date('Y-m-d H:i:s') }}</td>
                    <td>{{ date('Y-m-d H:i:s') }}</td>
                </tr>


            </tbody>
        </table>



        {{-- End of report Content --}}

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
