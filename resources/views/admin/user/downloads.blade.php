@extends('layouts.user')



@section('content')

    <!-- Header -->
    <header id="header" class="ex-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>DOWNLOADS</h1>

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->


    <!-- Breadcrumbs -->
    <div class="ex-basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs">
                        <a href="/#home">Home</a><i class="fa fa-angle-double-right"></i><span>Downloads</span>
                    </div> <!-- end of breadcrumbs -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of ex-basic-1 -->
    <!-- end of breadcrumbs -->

    <!-- Privacy Content -->
    <div class="ex-basic-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="text-container">
                        <div class="table-container">
                            <h3 class="tabledown">Downloads</h3>
                            {{-- <h6>After downloading, unzip the file and click the apk file to install the e-Serbisyo android application.</h6> --}}
                            <table class=table>
                            <thead>
                                <tr>
                                    <th>Version</th>
                                    <th>Description</th>
                                    <th>Date-Release</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($androids as $android)
                                    <tr>

                                        <td data-label="Version">
                                            <a href="{{ $android->url }}" target="_blank">
                                               {{ $android->version }}
                                            </a>
                                        </td>
                                        <td data-label="Description">{{ $android->description }}</td>
                                        <td data-label="Date-Release">{{ date_format($android->created_at,'d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <p>No android application yet</p>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                    </div> <!-- end of text-container-->
                    <a class="btn-outline-reg" href="index.html">BACK</a>
                </div> <!-- end of col-->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of ex-basic-2 -->
    <!-- end of privacy content -->

    <!-- Breadcrumbs -->
    <div class="ex-basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs">
                        <a href="/">Home</a><i class="fa fa-angle-double-right"></i><span>Downloads    </span>
                    </div> <!-- end of breadcrumbs -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of ex-basic-1 -->
    <!-- end of breadcrumbs -->

        <!-- Footer -->
    @include('admin.user.homeinc.footer')






@endsection
