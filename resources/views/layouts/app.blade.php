<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <!-- <script src="{{ asset('js/Chart.min.js') }}"></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- Bootstrap CSS File -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link href="/welcome/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- /welcome/libraries CSS Files -->
    <link href="/welcome/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link href="/welcome/lib/animate/animate.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/ionicons/css/ionicons.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/magnific-popup/magnific-popup.css" rel="stylesheet"> -->

    <!-- Main Stylesheet File -->
    <link href="/welcome/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <div id="app">
        <header id="header">
            <div class="container">

                <div id="logo" class="pull-left">

                    <a href="{{ route('welcome', [], false) }}"><img src="/welcome/img/TrustNew.png" height="60" width="80" alt="" title="" /></a>
                </div>

                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li><a href="{{ route('staff', [], false) }}">Home</a></li>
                        @if (Session::get('staffdata')['role'] <= 1) <li class="menu-has-children"><a href="">Admin</a>
                            <ul>
                                <li><a href="{{ route('admin', [], false) }}">Management</a></li>
                                <li> <a class=" dropdown-item" href="{{ route('reports', [], false) }}">Reports</a>
                                </li>

                            </ul>
                            </li>
                            @endif
                            <li> <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a></li>
                            <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <li class="menu-has-children"><a href="">Help</a>
                            <ul>
                                <li><a href="{{ route('feedback', [], false) }}">Feedback</a></li>
                                <li> <a class=" dropdown-item" href="{{ route('home', [], false) }}">Quick Guide</a>
                                </li>
                                <li><a href="{{ route('adminlist', [], false) }}">Admin List</a></li>

                            </ul>
                            </li>

                    </ul>
                </nav><!-- #nav-menu-container -->

            </div>

        </header><!-- #header -->


        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
@yield('page-js')
<!-- JavaScript /welcome/libraries -->
<!-- <script src="/welcome/lib/jquery/jquery.min.js"></script> -->
<!-- <script src="/welcome/lib/jquery/jquery-migrate.min.js"></script> -->
<!-- <script src="/welcome/lib/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- <script src="/welcome/lib/easing/easing.min.js"></script> -->
<script src="/welcome/lib/superfish/hoverIntent.js"></script>
<script src="/welcome/lib/superfish/superfish.min.js"></script>
<script src="/welcome/lib/wow/wow.min.js"></script>
<script src="/welcome/lib/owlcarousel/owl.carousel.min.js"></script>
<!-- <script src="/welcome/lib/magnific-popup/magnific-popup.min.js"></script> -->
<script src="/welcome/lib/sticky/sticky.js"></script>

<!-- Contact Form JavaScript File -->
<script src="/welcome/contactform/contactform.js"></script>

<!-- Template Main Javascript File -->
<script src="/welcome/js/main.js"></script>

</html>
