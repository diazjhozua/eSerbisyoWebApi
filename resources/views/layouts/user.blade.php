<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- SEO Meta Tags -->
        <meta name="description" content="Barangay Cupang Application to streamline your barangay needs and take advantage of our line services.">
        <meta name="author" content="Jhozua Diaz">
        <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
        <meta property="og:site_name" content="Barangay Cupang" /> <!-- website name -->
        <meta property="og:site" content="{{ route('home') }}" /> <!-- website link -->
        <meta property="og:title" content="BrgCupang E-Serbisyo"/> <!-- title shown in the actual shared post -->
        <meta property="og:description" content="Barangay Cupang Information Desimination and Certificate Issuance System" /> <!-- description shown in the actual shared post -->
        <meta property="og:image" content="{{ asset('assets/img/user/logo.png')}}" /> <!-- image link, make sure it's jpg -->

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Barangay Cupang</title>

        <!-- Styles Custom Font-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">

        <!-- Styles CSS -->
        <link href="{{ asset('css/user/bootstrap.css')}}" rel="stylesheet">
        <link href="{{ asset('css/user/fontawesome-all.css')}}" rel="stylesheet">
        <link href="{{ asset('css/user/swiper.css')}}" rel="stylesheet">
        <link href="{{ asset('css/user/magnific-popup.css')}}" rel="stylesheet">
        <link href="{{ asset('css/user/styles.css')}}" rel="stylesheet">
        <link href="{{ asset('css/user/fontawesome.css')}}" rel="stylesheet">

        <!-- Favicon  -->
        <link rel="icon" href="assets/img/user/favicon.ico">

        {{-- Toastr Css --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">


    </head>
    <body data-spy="scroll" data-target=".fixed-top">

        @yield('content')
        <!-- Preloader -->
        <div class="spinner-wrapper">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
        <!-- end of preloader -->

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
            <div class="container">

                <!-- Text Logo - Use this if you don't have a graphic logo -->
                <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Tivo</a> -->

                <!-- Image Logo -->
                <a class="navbar-brand logo-image" href="/"><img src="{{ asset('assets/img/user/logo.png')}}" alt="Barangay Cupang" style ="height:50px; width:50px"></a>

                <!-- Mobile Menu Toggle Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-awesome fas fa-bars"></span>
                    <span class="navbar-toggler-awesome fas fa-times"></span>
                </button>
                <!-- end of mobile menu toggle button -->

                <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="/#header">HOME <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="/#features">FEATURES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="/#details">DETAILS</a>
                        </li>

                        <!-- Dropdown Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle page-scroll" href="/#video" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">APPLICATION</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/downloads"><span class="item-text">DOWNLOADS</span></a>
                                <div class="dropdown-items-divide-hr"></div>
                                <a class="dropdown-item" href="/terms"><span class="item-text">TERMS CONDITIONS</span></a>
                                <div class="dropdown-items-divide-hr"></div>
                                <a class="dropdown-item" href="/privacy"><span class="item-text">PRIVACY POLICY</span></a>
                            </div>
                        </li>
                        <!-- end of dropdown menu -->

                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="/#pricing">PRICING</a>
                        </li>
                    </ul>
                    @auth
                        <span class="nav-item">
                        @if (Auth::user()->user_role_id < 5)
                            <a class="btn-outline-sm" href="{{ route('admin.dashboard.index') }}">ADMIN PAGE</a>
                        @elseif (Auth::user()->user_role_id == 5)
                            {{-- Information staff --}}
                            <a class="btn-outline-sm" href="{{ route('admin.users.index') }}">ADMIN PAGE</a>
                        @elseif (Auth::user()->user_role_id == 6)
                            {{-- Certificate staff --}}
                        @elseif (Auth::user()->user_role_id == 7)
                            {{-- Taskforce staff --}}
                            <a class="btn-outline-sm" href="{{ route('admin.reports.index') }}">ADMIN PAGE</a>
                        @endif


                        </span>
                    @endauth

                    @guest
                        <span class="nav-item">
                            <a class="btn-outline-sm" href="/login">LOG IN</a>
                        </span>
                    @endguest

                </div>
            </div> <!-- end of container -->
        </nav> <!-- end of navbar -->
        <!-- end of navigation -->


        <!-- Scripts -->
        <script src="{{ asset('js/user/jquery.min.js') }}"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
        <script src="{{ asset('js/user/popper.min.js') }}"></script> <!-- Popper tooltip library for Bootstrap -->
        <script src="{{ asset('js/user/bootstrap.min.js') }}"></script> <!-- Bootstrap framework -->
        <script src="{{ asset('js/user/jquery.easing.min.js') }}"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
        <script src="{{ asset('js/user/swiper.min.js') }}"></script> <!-- Swiper for image and text sliders -->
        <script src="{{ asset('js/user/jquery.magnific-popup.js') }}"></script> <!-- Magnific Popup for lightboxes -->
        <script src="{{ asset('js/user/validator.min.js') }}"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
        <script src="{{ asset('js/user/scripts.js') }}"></script> <!-- Custom scripts -->

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}} "></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        {{-- For Validation --}}
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

        {{-- Font Awesome --}}
        <script src="https://kit.fontawesome.com/3cc49842c0.js" crossorigin="anonymous"></script>

        {{-- Toastr --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" crossorigin="anonymous"></script>

        {{-- Helper Function --}}
        <script src="{{ asset('js/helper.js')}}"></script>

        @yield('page-js')

    </body>
</html>
