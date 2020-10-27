<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} : @yield('title', Route::current()->uri()) </title>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <!-- <script src="{{ asset('js/Chart.min.js') }}"></script> -->
    <link rel="icon" sizes="32x32" type="image/png" href="/images/trust-stayhome.png" />
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- Bootstrap CSS File -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link href="/welcome/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/css/theme/lumen.bootstrap.min.css" rel="stylesheet"> -->

    <!-- /welcome/libraries CSS Files -->
    <link href="/welcome/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link href="/welcome/lib/animate/animate.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/ionicons/css/ionicons.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"> -->
    <!-- <link href="/welcome/lib/magnific-popup/magnific-popup.css" rel="stylesheet"> -->

    <!-- Main Stylesheet File -->
    <link href="/welcome/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    @yield('page-css')
</head>

<body>
    <div id="app">
        <header id="header">
            <div class="container">
                <div id="logo" class="pull-left">
                    <a href="{{ route('staff', [], false) }}"><img src="/images/trust-stayhome.png" height="50" alt="" title="" /></a>
                </div>

                <nav id="nav-menu-container">
                      <ul class="nav-menu">
                        <li><a class="nav-link" href="{{ route('hofs', [], false) }}" title="Hall Of Fame"><i class="fa fa-trophy" style="color:blue"></i></a></li>
                        <li><a class="nav-link" href="{{ route('news', [], false) }}" title="Latest broadcast from the management">News <i class="fa fa-newspaper-o" style="color:orange"></i></a></li>

                        @guest
                        @else
                        <!-- <li><a href="{{ route('staff', [], false) }}">Home</a></li> -->
                        @if (Auth::user()->role <= 1)
                        <li class="menu-has-children"><a href="#">Admin</a>
                          <ul>
                              <li><a class="dropdown-item" href="{{ route('admin', [], false) }}">Management</a></li>

                          </ul>
                        </li>
                        @endif
                        <li class="menu-has-children"><a href="#">Menu</a>
                          <ul>
                              <li><a class="dropdown-item" href="{{ route('staff', [], false) }}">Home</a></li>
                              <li class="menu-has-children"><a href="#">MCO Initiative</a>
                                <ul>
                                    <li><a class="dropdown-item" href="{{ route('mco.reqform', [], false) }}">Request Travel Acknowledgement</a></li>
                                    <li><a class="dropdown-item" href="{{ route('mco.ackreqs', [], false) }}">Acknowledge Travel Request</a></li>
                                </ul>
                              </li>
                              <li><a class="dropdown-item" href="{{ route('staff.find', [], false) }}">Staff Finder</a></li>
                              <li><a class="dropdown-item" href="{{ route('area.list', [], false) }}">Meeting Area</a></li>
                              <li><a class="dropdown-item" href="{{ route('reports', [], false) }}">Reports</a></li>
                              <li><a class="dropdown-item" href="{{ route('poll.index', [], false) }}"><i class="fa fa-line-chart" style="color:green"></i> Polls <span class="badge badge-danger">&nbsp;beta!&nbsp;</span></a></li>

                          </ul>
                        </li>
                        <li class="menu-has-children"><a href="#">Help</a>
                          <ul>
                              <li><a class="dropdown-item" href="{{ route('policy', [], false) }}">Privacy Policy</a></li>
                              <li><a class="dropdown-item" href="{{ route('feedback', [], false) }}">Feedback</a></li>
                              <li><a class="dropdown-item" href="{{ route('adminlist', [], false) }}">Admin List</a></li>

                          </ul>
                        </li>
                        @endif
                        <li><a class="nav-link" href="{{ route('guides', [], false) }}" title="Collection of user guides">Guides</i></a></li>
                        <li>
                          @guest
                          <a class="dropdown-item" href="{{ route('login', [], false) }}">Login</a>
                          @else
                          <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                              {{ __('Logout') }}
                          </a>
                          @endguest

                        </li>
                        <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </nav><!-- #nav-menu-container -->

            </div>

        </header><!-- #header -->


        <main class="py-4">
          @if( $enonmen3->count() > 0)
          <div class="alert alert-info alert-dismissible mx-3">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong><i class="fa fa-bullhorn"></i> Announcement!</strong>
            <ul class="mb-0">
              @foreach($enonmen3 as $ann)
              <li>
                {{ $ann->content }}
                @if(isset($ann->url))
                - <a href="{{ $ann->url }}" target="_blank">
                  @if(isset($ann->url_text))
                  {{ $ann->url_text }}
                  @else
                  Click here
                  @endif
                </a>
                @endif
              </li>
              @endforeach
            </ul>
          </div>
          @endif
            @yield('content')
        </main>
    </div>
</body>
@yield('page-js')
<script src="/welcome/lib/superfish/hoverIntent.js"></script>
<script src="/welcome/lib/superfish/superfish.min.js"></script>
<script src="/welcome/lib/wow/wow.min.js"></script>
<script src="/welcome/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="/welcome/lib/sticky/sticky.js"></script>

<!-- Contact Form JavaScript File -->
<script src="/welcome/contactform/contactform.js"></script>

<!-- Template Main Javascript File -->
<script src="/welcome/js/main.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga_key') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', "{{ config('app.ga_key') }}");
</script>

</html>
