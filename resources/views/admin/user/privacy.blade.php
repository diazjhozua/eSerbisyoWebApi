
    @extends('layouts.user')



@section('content')

    <!-- Header -->
    <header id="header" class="ex-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Privacy Policy</h1>
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
                        <a href="/">Home</a><i class="fa fa-angle-double-right"></i><span>Privacy Policy</span>
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
                        <h3>Private Data Barangay Cupang E-Serbisyo Receive And Collect</h3>
                        <p style="text-align:justify;">The actions you undertake on our Website, Platforms, and Applications, as well as the type of hardware and software you are using, are all automatically collected and received by Barangay Cupang E- Serbisyo from your  mobile device. When you use our app or visit our website, for example, the data we stated is sent straight to us. Additionally, IP addresses, device types, and browsers used to access our site will be gathered automatically. We wanted you to understand our perspective and be aware of what information is being gathered about you. Furthermore, we will never disclose the information we have received with anyone else, and it will only be stored in our system.</p>
                        <p>We collect some Personal Information about you when you first create an account and when you use the Services, such as: </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled li-space-lg indent">
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body"  style="text-align: justify;">Because we utilize an online delivery system, we need to know where you use your computer and mobile devices.</div>
                                    </li>
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">When you create an account, your complete name, username, email address, and other contact information are saved. We use it to verify that you are a resident of Barangay Cupang and to respond to your online request.</div>
                                    </li>
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">When you register, you will be given a unique user ID that will be used in our database.</div>
                                    </li>
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">The data in the Barangay Cupang E-Serbisyo System is continuously backed up and will not fail.</div>
                                    </li>


                                </ul>
                            </div> <!-- end of col -->

                            <div class="col-md-6">
                                <ul class="list-unstyled li-space-lg indent">
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">Your billing address, as well as any other information required to complete any financial transaction, must be acquired by our system specifically when using our delivery system. Online payment data, such as Gcash, Paymaya, and other online payment applications, will also be gathered.</div>
                                    </li>
                                    <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">Messages, files, reports, profiles, and images you have published in our service are likewise recorded in our system and combined with your profile.</div>
                                    </li>
                                   <li class="media">
                                        <i class="fas fa-square"></i>
                                        <div class="media-body" style="text-align: justify;">Your IP Address and, when applicable, timestamp related to your consent and confirmation of consent but please make sure it does</div>
                                    </li>
                                </ul>
                            </div> <!-- end of col -->
                        </div> <!-- end of row -->
                    </div> <!-- end of text-container-->



                    <div class="text-container">
                        <h3>For the Barangay Cupang E-Barangay System, we process content for users</h3>
                        <p style="text-align: justify;">The Barangay Cupang E-Barangay System is an application that would give inhabitants of Barangay Cupang access to online services. To be able to embrace and provide the greatest experience to users, we encourage next generation services. We are pleased to finally present our technology, which can be used to request important documents from the barangay via  online. We also implemented an online delivery system to reduce residents' need to travel outside and, as a result, maintain the resident's physical distancing due to today's pandemic.</p>
                    </div> <!-- end of text container -->

                    <div class="text-container">
                        <h3>Acceptance of Our System's Use</h3>
					    <p style="text-align: justify;">You consent to the collection, transmission, storage, disclosure, and use of your Personal Information in the manner set out in this Privacy Policy by using our services or submitting or collecting any Personal Information via the Services. If you do not agree to your Personal Information being used in these ways, please stop using our Services to be able for us to guarantee that you did not agree with our private policy.</p>
                    </div> <!-- end of text-container -->


                        <div class="col-md-6">

                            <!-- Privacy Form -->
                            <

                        </div> <!-- end of col-->
                    </div> <!-- end of row -->
                    <a class="btn-outline-reg" href="/home#home">BACK</a>
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
                        <a href="/#header">Home</a><i class="fa fa-angle-double-right"></i><span>Privacy Policy</span>
                    </div> <!-- end of breadcrumbs -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of ex-basic-1 -->
    <!-- end of breadcrumbs -->

    <!-- Footer -->
    @include('admin.user.homeinc.footer')

@endsection
