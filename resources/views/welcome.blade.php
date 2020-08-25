<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0,maximum-scale=1,user-scalable=no" name="viewport">
    <!-- <meta name=”viewport” content=”width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no”> -->
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
    <link href="/welcome/css/style.css" rel="stylesheet">

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
        <div class="container">

            <div id="logo" class="pull-left">

                <!-- <!-- Uncomment below if you prefer to use an image logo
                <a class="v1" href="#body"><img src="/welcome/img/TM_LOGO.png" height="40" width="100" alt="" title=""
                        style="padding-right: 20px;" /></a> -->
                <a href="#body"><img src="/welcome/img/TrustNew.png" height="40" width="60" alt="" title="" /></a>
            </div>

            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li class="menu-active"><a href="#body">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Features</a></li>
                    <li class="menu-has-children"><a href="#">Guide</a>
                      <ul>
                          <li><a class="dropdown-item" href="{{ route('home', [], false) }}">trUSt General Guide</a></li>
                          <li><a class="dropdown-item" href="{{ route('booking_faq', [], false) }}">Space Booking FAQ</a></li>
                      </ul>
                    </li>
                    <!-- <li class="menu-has-children"><a href="">Drop Down</a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
              <li><a href="#">Drop Down 5</a></li>
            </ul>
          </li> -->
                    <li><a id="downloada" href="#downloadmodal" role="button" data-toggle="modal">Download</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a id="logina" href="#loginModal" role="button" data-toggle="modal">Login</a></li>
                </ul>
            </nav><!-- #nav-menu-container -->

        </div>

    </header><!-- #header -->

    <!--==========================
    Intro Section
  ============================-->
    <section id="intro">

        <div id="intro-carousel" class="owl-carousel">
            <div class="item"
                style="background-image: url('/welcome/img/intro-carousel/b1t.jpg');"></div>
            <div class="item"
                style="background-image: url('/welcome/img/intro-carousel/b2r.jpg');">
            </div>
            <div class="item" style="background-image: url('/welcome/img/intro-carousel/b3u.jpg');">
            </div>
            <div class="item"
                style="background-image: url('/welcome/img/intro-carousel/b4s.jpg');">
            </div>
            <div class="item"
                style="background-image: url('/welcome/img/intro-carousel/b5t.jpg');">
            </div>

        </div>

    </section><!-- #intro -->

    <main id="main">

        <!--==========================
      About Section
    ============================-->
        <section id="about" class="wow fadeInUp">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 about-img">
                        <img src="/welcome/img/agile.jpg" alt="">
                    </div>

                    <div class="col-lg-6 content text-justify">
                        <div class="section-header">
                            <h2>AGILE OFFICE .</h2>
                        </div>

                        <h3>A GIT AGILE DEVELOPMENT CENTRE.</h3>
                        <span>In order for GIT to be more effective in delivering high quality solution with faster
                            delivery,
                            teamwork is crucial. We need to change the way we work.how we deal with each other. We need
                            to be more
                            collaborative</span>

                        <ul>
                            <li><i class="ion-android-checkmark-circle"></i>
                                <b>SPACE OPTIMIZATION:</b>
                                Less square footage required to host the employees. Bring your laptop only will be
                                adhered! (staff will
                                be provided drawer to keep their belonging but it is not dedicated)
                                .</li>
                            <li><i class="ion-android-checkmark-circle"></i>
                                <b>PROMOTE 5S:</b>
                                Safety and good housekeeping practices in all office space. A must criteria for
                                Hot-Desking
                            </li>
                            <li><i class="ion-android-checkmark-circle"></i>
                                <b> FLEXIBILITY:</b>
                                Don’t have to commit to a single layout – will come in handy during structure
                                re-organization.
                            </li>
                            <li><i class="ion-android-checkmark-circle"></i>
                                <b>FREE & EASY:</b>
                                We determine the floor, you determine your place. Get to know your new neighbor
                                everyday!

                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </section><!-- #about -->

        <!--==========================
      Services Section
    ============================-->
        <section id="services">
            <div class="container">
                <div class="section-header">
                    <h2>Features</h2>
                    <p>To realize the “Open Office’ and ‘Hot-Desking” implementation, the trUSt mobile app has
                        been developed with the following features:
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="box wow fadeInLeft bg-primary rounded" data-wow-delay="0.2s">
                            <div class="icon"><i class="fa fa-sign-in"></i></div>
                            <h4 class="title"><a href="">Agile Workspace</a></h4>
                            <p class="description">Enable employees to check in at any hot desk by scaning the QR code.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="box wow fadeInLeft bg-success rounded" data-wow-delay="0.2s">
                            <div class="icon"><i class="fa fa-pencil-square-o"></i></div>
                            <h4 class="title"><a href="">Diary</a></h4>
                            <p class="description">Record the tasks that you have performed for the day.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="box wow fadeInLeft bg-warning rounded" data-wow-delay="0.2s">
                            <div class="icon"><i class="fa fa-location-arrow"></i></div>
                            <h4 class="title"><a href="">Flexi Space</a></h4>
                            <p class="description">Check-in your current working location.</p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="box wow fadeInLeft bg-info shadow rounded">
                            <div class="icon"><i class="fa fa-search"></i></div>
                            <h4 class="title"><a href="">Staff Finder</a></h4>
                            <p class="description">Search a staff to see their relevant information.</p>
                        </div>
                    </div>

                    <!-- <div class="col-lg-6">
                        <div class="box wow fadeInRight bg-primary rounded">
                            <div class="icon"><i class="fa fa-bar-chart"></i></div>
                            <h4 class="title"><a href="">Reporting</a></h4>
                            <p class="description">Trust application provide pie-chart reporting to show the occupied of
                                each office
                                level.</p>
                        </div>
                    </div> -->






                </div>

            </div>
        </section><!-- #services -->

        <!--==========================
      Clients Section
    ============================-->
        <!--==========================
      Leadership Section
    ============================-->
        <!--
        <section id="testimonials" class="wow fadeInUp">
            <div class="container">
                <div class="section-header">
                    <h2>What our leaders say</h2>

                </div>
                <div class="owl-carousel testimonials-carousel">
                    <div class="testimonial-item">
                        <div class="row pl-5 ml-5">
                            <div class="md-col-6">
                                <img src="/welcome/img/boss/en_izhan.png" class="testimonial-img" alt="">
                                <h3>En Izhan Ayob</h3>
                                <h3>VP GIT</h3>
                            </div>
                            <div class="md-col-6">
                                <p>
                                    <img src="/welcome/img/quote-sign-left.png" class="quote-sign-left" alt="">
                                    Great App!
                                    <img src="/welcome/img/quote-sign-right.png" class="quote-sign-right" alt="">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="row pl-5 ml-5">
                            <div class="md-col-6">
                                <img src="/welcome/img/boss/pn-nana.png" class="testimonial-img" alt="">
                                <h3>Puan Naziana</h3>
                                <h3>GM ITAS</h3>
                            </div>
                            <div class="md-col-6">
                                <p>
                                    <img src="/welcome/img/quote-sign-left.png" class="quote-sign-left" alt="">
                                    trust is good.
                                    <img src="/welcome/img/quote-sign-right.png" class="quote-sign-right" alt="">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="row pl-5 ml-5">
                            <div class="md-col-6">
                                <img src="/welcome/img/boss/en_shamsul.png" class="testimonial-img" alt="">
                                <h3>Encik Shamsul</h3>
                                <h3>GM ITEC</h3>
                            </div>
                            <div class="md-col-6">
                                <p>
                                    <img src="/welcome/img/quote-sign-left.png" class="quote-sign-left" alt="">
                                    trust is good.
                                    <img src="/welcome/img/quote-sign-right.png" class="quote-sign-right" alt="">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
      -->
        <!-- #testimonials -->



        <!--==========================
      Our Team Section
    ============================-->
        <!-- <section id="team" class="wow fadeInUp">
            <div class="container">
                <div class="section-header">
                    <h2>Our Team</h2>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="member">
                            <div class="pic"><img src="/welcome/img/team-1.jpg" alt=""></div>
                            <div class="details">
                                <h4>Walter White</h4>
                                <span>Chief Executive Officer</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="member">
                            <div class="pic"><img src="/welcome/img/team-2.jpg" alt=""></div>
                            <div class="details">
                                <h4>Sarah Jhinson</h4>
                                <span>Product Manager</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="member">
                            <div class="pic"><img src="/welcome/img/team-3.jpg" alt=""></div>
                            <div class="details">
                                <h4>William Anderson</h4>
                                <span>CTO</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="member">
                            <div class="pic"><img src="/welcome/img/team-4.jpg" alt=""></div>
                            <div class="details">
                                <h4>Amanda Jepson</h4>
                                <span>Accountant</span>
                                <div class="social">
                                    <a href=""><i class="fa fa-twitter"></i></a>
                                    <a href=""><i class="fa fa-facebook"></i></a>
                                    <a href=""><i class="fa fa-google-plus"></i></a>
                                    <a href=""><i class="fa fa-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>#team -->

        <!--==========================
      Contact Section
    ============================-->
        <section id="contact" class="wow fadeInUp">
            <div class="container">
                <div class="section-header">
                    <h2>Contact Us</h2>
                    <p>Send us your feedback.</p>
                </div>

                <!-- <div class="row contact-info">

                    <div class="col-md-4">
                        <div class="contact-address">
                            <i class="ion-ios-location-outline"></i>
                            <h3>Address</h3>
                            <address>Level 8 North Menara Telekom Malaysia</address>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-phone">
                            <i class="ion-ios-telephone-outline"></i>
                            <h3>Phone Number</h3>
                            <p><a href="tel:+155895548855">+1 5589 55488 55</a></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-email">
                            <i class="ion-ios-email-outline"></i>
                            <h3>Email</h3>
                            <p><a href="mailto:info@trust.tm.com.my">info@trust.tm.com.my</a></p>
                        </div>
                    </div>

                </div> -->
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
                                placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                            <div class="validation"></div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" id="title" class="form-control" name="title"
                                placeholder="Subject" data-rule="minlen:4"
                                data-msg="Please enter at least 8 chars of subject" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="content" name="content" rows="5" data-rule="required"
                                data-msg="Please write something for us" placeholder="Message"></textarea>
                            <div class="validation"></div>
                        </div>
                        <div class="text-center"><button type="submit">Send Message</button></div>
                    </form>
                </div>

            </div>
        </section><!-- #contact -->



        <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="text-align:center;">
                        <h2>{{ __('Login') }}</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    @if ( isset($loginerror) )
                    <div class="alert alert-{{ $type }}" role="alert">
                        {{ $loginerror }}
                    </div>
                    @endif
                    <div class="modal-body">
                        <form method="POST" action="{{ route('login', [], false) }}">
                            @csrf

                            <div class="form-group row">
                                <label for="staff_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Staff ID') }}</label>

                                <div class="col-md-6">
                                    <input id="staff_id" type="text"
                                        class="form-control{{ $errors->has('staff_id') ? ' is-invalid' : '' }}"
                                        name="staff_id" value="{{ old('staff_id') }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
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
                                <div class="col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                                <a href="{{ route('register', [], false) }}">New User?</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="downloadmodal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="width:100%;">
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

    </main>

    <!--==========================
    Footer
  ============================-->
    <footer id="footer" style="background-color: orange;color: orange">
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

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

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
    <script src="/welcome/js/main.js"></script>

</body>

</html>
