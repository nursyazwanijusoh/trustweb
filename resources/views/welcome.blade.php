<!DOCTYPE html>
<html lang="en">

<head>
    @php

    $styleCSS = 'welcome/css/style.css';
    $styleVersion = substr(md5(filemtime($styleCSS)), 0, 6);


    $enhanceCSS = 'welcome/css/enhancement.css';
    $enhanceVersion = substr(md5(filemtime($enhanceCSS)), 0, 6);

    @endphp

    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0,maximum-scale=1,user-scalable=no" name="viewport">
    <!--<meta name=”viewport” content=”width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no”> -->
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicons -->
    <link href="/welcome/img/TrustNew.png" rel="icon">
    <link href="/welcome/img/TrustNew.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700"
        rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="/welcome/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- /welcome/libraries CSS Files -->
    <link href="/welcome/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/welcome/lib/animate/animate.min.css" rel="stylesheet">
    <link href="/welcome/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="/welcome/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/welcome/lib/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="/welcome/lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->

    <link rel="stylesheet" href="{{$styleCSS}}?v={{$styleVersion}}">
    <link rel="stylesheet" href="{{$enhanceCSS}}?v={{$enhanceVersion}}">

    <style>
    .vl {
        border-left: 6px solid green;
        height: 50px;

    }
    </style>

    <!-- =======================================================
    Theme Name: Reveal
    Theme URL: https://bootstrapmade.com/reveal-bootstrap-corporate-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->



</head>

<body id="body">


    <!--==========================
    Top Bar
  ============================-->
    <!-- <section id="topbar" class="d-none d-lg-block" style="background-color: orange">
        <div class="container clearfix">
            <div class="contact-info float-left">
                <i class="fa fa-envelope-o"></i> <a href="mailto:contact@trust.tm.com.my">contact@trust.tm.com.my</a>
                <i class="fa fa-phone"></i> +1 5589 55488 55
            </div>
        </div>
    </section> -->

    <!--==========================
    Header
  ============================-->
    <header id="header">


        <div id="logo" class="pull-left ">
            <a href="#body"><img src="/welcome/img/trust_white.png" height="45" alt="" title="" /></a>

        </div>

        <nav id="nav-menu-container">
            <ul class="nav-menu">

                <li class="menu-has-children" style="display:table-cell"><a href="#">Guide</a>
                    <ul>
                        <li><a class="dropdown-item" href="{{ route('home', [], false) }}">trUSt General Guide</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('booking_faq', [], false) }}">Space Booking
                                FAQ</a></li>
                    </ul>
                </li>

                <li><a id="contacta" href="#contact"><span class="btn btn-link">
                            <i class="fa fa-envelope-o" style="font-size:1.5em"></i>
                        </span>
                    </a>
                </li>
                <li><a id="logina" href="#loginModal" role="button" data-toggle="modal"><span
                            class="btn btn-primary">Login</span></a></li>
            </ul>
        </nav><!-- #nav-menu-container -->
    </header><!-- #header -->



    <main id="main">

        <!--==========================
      About Section
    ============================-->
        <section id="about" class="wow fadeInUp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 border smaller05"
                        style="background-image:url('/welcome/img/home.png');background-size: cover;">

                        <div class="ml-3 mt-5 ">
                            <div class="section-header">
                                <h2>ABOUT TRUST.</h2>
                            </div>
                            <p>A GIT AGILE DEVELOPMENT CENTRE.</p>
                            <p>In order for GIT to be more effective in delivering high quality solution with faster
                                delivery,
                                teamwork is crucial. We need to change the way we work.how we deal with each other. We
                                need
                                to be more
                                collaborative</p>

                            <div class="font-hani">
                                <p>

                                    <b>SPACE OPTIMIZATION:</b>
                                    Less square footage required to host the employees. Bring your laptop only will be
                                    adhered! (staff will
                                    be provided drawer to keep their belonging but it is not dedicated).
                                </p>
                                <p>
                                    <b>PROMOTE 5S:</b>
                                    Safety and good housekeeping practices in all office space. A must criteria for
                                    Hot-Desking

                                </p>
                                <p>
                                    <b> FLEXIBILITY:</b>
                                    Don’t have to commit to a single layout – will come in handy during structure
                                    re-organization.
                                </p>
                                <p>

                                    <b>FREE & EASY:</b>
                                    We determine the floor, you determine your place. Get to know your new neighbor
                                    everyday!
                                </p>
                            </div>


                        </div>
                    </div>

                    <div class="col-lg-6 content text-justify border box-hani">
                        <div class="ml-3 mt-5">
                            <div class="container">
                                <div class="section-header mb-1">
                                    <h2>Features.</h2>
                                    <p>To realize the “Open Office’ and ‘Hot-Desking” implementation, the trUSt mobile
                                        app has been developed with the following features:
                                    </p>
                                </div>

                                <div class="row p-1 mt-0 smaller05">
                                    <div class="col-lg-12">
                                        <div class="row wow fadeInLeft box-zahid border p-1" data-wow-delay="0.2s">
                                            <div class="col-2 ">
                                                <img src="/img/desk.png" class="img-fluid">
                                            </div>
                                            <div class="col-9">
                                                <b>AGILE WORKSPACE</b> <br />
                                                <div class="smaller15">
                                                    Enable employees to check in at any hot desk by scanning the QR code
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-1">
                                        <div class="row wow fadeInLeft box-zahid border p-1" data-wow-delay="0.2s">
                                            <div class="col-2">
                                                <img src="/img/diary.png" class="img-fluid">
                                            </div>
                                            <div class="col-9">
                                                <b>UPDATE DIARY</b> <br />
                                                <div class="smaller15">
                                                    Record the tasks that you have performed for the day.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-1">
                                        <div class="row wow fadeInLeft box-zahid border p-1" data-wow-delay="0.1s">
                                            <div class="col-2">
                                                <img src="/img/map.png" class="img-fluid">
                                            </div>
                                            <div class="col-9">
                                                <b>FLEXI SPACE</b> <br />
                                                <div class="smaller15">
                                                    Check-in your current working location.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-1">
                                        <div class="row wow fadeInLeft box-zahid border p-1">
                                            <div class="col-2">
                                                <img src="/img/finder.png" class="img-fluid">
                                            </div>
                                            <div class="col-9">
                                                <b>STAFF FINDER</b> <br />
                                                <div class="smaller15">
                                                    Search a staff to see their relevant information.
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!--row -->




            </div>
        </section><!-- #about -->



        <!--==========================
     Download
    ============================-->
        <section id="download" class="wow fadeInUp mt-0">
            <div class="container-fluid mt-0">
                <div class="row">
                    <!-- rowcdownload -->
                    <div class="col-lg-12">
                        <div class="section-header">
                            <h2>DOWNLOAD APP.</h2>
                        </div>
                        TRUST mobile application also available in Appstore and Android.
                    </div>
                </div> <!-- /rowcdownload -->
                <div class="row">
                    <!-- rowcdownload -->
                    <div class="col-lg-12">
                        <div class="float-right">
                            <a href="{{ route('app.down', ['type' => 'apk'], false) }}"><img
                                    src="/welcome/img/download_android.png" height="80" alt="" title="" /></a>

                            <a
                                href="itms-services://?action=download-manifest&url=https://trust.tm.com.my/storage/trust.plist"><img
                                    src="/welcome/img/download_ios.png" height="80" alt="" title="" /></a>

                        </div>
                    </div>
                </div> <!-- /rowcdownload -->

            </div>
        </section>




        <!--==========================
      Contact Section
    ============================-->

        <div id="contact" class="modal fade" tabindex="1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="container">
                        <div class="section-header">
                            <h2>Contact Us</h2>
                            <p>Send us your feedback.</p>
                        </div>


                    </div>


                    <div class="container">
                        <div class="form">
                            <div id="sendmessage">Your message has been sent. Thank you!</div>
                            <div id="errormessage"></div>
                            <form action="" method="post" role="form" class="contactForm">
                                @csrf
                                <input type="hidden" name="sos" value="web" />
                                <div class="form-group ">
                                    <input type="email" class="form-control" id="ctc" class="form-control" name="ctc"
                                        placeholder="Your Email" data-rule="email"
                                        data-msg="Please enter a valid email" />
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control" id="title" class="form-control" name="title"
                                        placeholder="Subject" data-rule="minlen:4"
                                        data-msg="Please enter at least 8 chars of subject" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" id="content" name="content" rows="5"
                                        data-rule="required" data-msg="Please write something for us"
                                        placeholder="Message"></textarea>
                                    <div class="validation"></div>
                                </div>
                                <div class="text-center"><button type="submit">Send Message</button></div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div><!-- #contact -->



        <div id="loginModal" class="modal fade" tabindex="1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    @if ( isset($loginerror) )
                    <div class="alert alert-{{ $type }}" role="alert">
                        {{ $loginerror }}
                    </div>
                    @endif
                    <div class="modal-body">

                        <div class="row no-gutter">
                            <div class="col-md-6 d-sm-none d-md-block box-zahid" descr="login pic">

                                <div class="card card-body  d-flex justify-content-center">
                                    <img src="/welcome/img/trust_white.png" title="" class="img-fluid" />
                                </div>
                            </div> <!-- login picture -->

                            <div class="col-md-6" descr="login form div">

                                <div class="modal-header section-header">
                                    <h2>{{ __('Sign In') }}</h2>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>

                                <form method="POST" action="{{ route('login', [], false) }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label for="staff_id"
                                            class="col-md-12 col-form-label">{{ __('Staff ID') }}</label>

                                        <div class="col-md-12 input-group">

                                            <span class="input-group-prepend">
                                                <button class="btn btn-secondary" type="button">
                                                    <i class="fa fa-user"></i>
                                                </button>
                                            </span>

                                            <input id="staff_id" type="text"
                                                class="form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}"
                                                name="staff_id" value="{{ old('staff_id') }}" required autofocus>

                                        </div>

                                        <label for="password"
                                            class="col-md-12 col-form-label">{{ __('Password') }}</label>

                                        <div class="col-md-12 input-group">

                                            <span class="input-group-prepend">
                                                <button class="btn btn-secondary" type="button">
                                                    <i class="fa fa-unlock"></i>
                                                </button>
                                            </span>


                                            <input id="password" type="password"
                                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                name="password" required>

                                            @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Login') }}
                                            </button>

                                            <a href="{{ route('register', [], false) }}">New User?</a>

                                        </div>
                                    </div>


                                    <div class="form-group row mb-0">
                                        <div class="col-md-12 p-3">
                                            Dear Users, <br />
                                            If you have any queries, problems or have not received any ID
                                            and password for FAST System, please log into IRIS Self Service System.

                                        </div>
                                    </div>


                                </form>
                            </div>
                            <!--login form div -->
                        </div> <!-- row-->
                    </div> <!-- modal-body -->
                </div>
            </div>
        </div>

        <!---

        <div id="downloadmodal" class="modal fade" tabindex="-13 role="dialog" aria-hidden="true" style="width:100%;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align:center;">
                        <h2>Download</h2>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('app.down', ['type' => 'apk'], false) }}"><img
                                            src="/welcome/img/download_android.png" height="80" width="100%" alt=""
                                            title="" /></a>

                                </div>

                            </div>

                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-8">
                                    <a
                                        href="itms-services://?action=download-manifest&url=https://trust.tm.com.my/storage/trust.plist"><img
                                            src="/welcome/img/download_ios.png" height="80" width="100%" alt=""
                                            title="" /></a>

                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
--->
    </main>

    <!--==========================
    Footer
  ============================-->
    <footer id="footer" style="background-color: orange;color: orange; display:none">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong>Reveal</strong>. All Rights Reserved
            </div>
            <div class="credits" style="color: orange">
                <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Reveal
        -->
                Designed by <a href="https://bootstrapmade.com/" style="color:orange;">BootstrapMade</a>

            </div>
        </div>
    </footer><!-- #footer -->
    <footer class="foot">

        <div class="foot-text">Copyright © 2020 Telekom Malaysia Berhad. All rights reserved.
        </div>

        <div class="float-right">
            <img class="foot-img " src="/welcome/img/footer-logo.png">
        </div>
        </div>
    </footer>



    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    @php
    $mainJS = 'welcome/js/main.js';
    $mainJSVersion = substr(md5(filemtime($mainJS)), 0, 6);
    @endphp
    <!-- JavaScript /welcome/libraries -->
    <script src="/welcome/lib/jquery/jquery.min.js"></script>
    <script src="/welcome/lib/jquery/jquery-migrate.min.js"></script>
    <script src="/welcome/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/welcome/lib/easing/easing.min.js"></script>
    <script src="/welcome/lib/superfish/hoverIntent.js"></script>
    <script src="/welcome/lib/superfish/superfish.min.js"></script>
    <script src="/welcome/lib/wow/wow.min.js"></script>
    <script src="/welcome/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="/welcome/lib/magnific-popup/magnific-popup.min.js"></script>
    <script src="/welcome/lib/sticky/sticky.js"></script>

    <!-- Contact Form JavaScript File -->
    <script src="/welcome/contactform/contactform.js"></script>

    <!-- Template Main Javascript File -->

    <script src="/{{$mainJS}}?v={{$mainJSVersion}}"></script>

</body>

</html>