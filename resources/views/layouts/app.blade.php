<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sistem Peminjaman Alat</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/css/owl.theme.default.min.css') }}">
    <link href="{{ asset('vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Preloader start -->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!-- Preloader end -->

    <!-- Main wrapper start -->
    <div id="main-wrapper">

        <!-- Nav header start -->
        <div class="nav-header">
    <a href="{{ 
        Auth::check() ? 
            (Auth::user()->role == 'admin' ? route('admin.dashboard') : 
            (Auth::user()->role == 'petugas' ? route('petugas.dashboard') : route('peminjam.dashboard'))) 
            : route('login') 
    }}" class="brand-logo">
            <img class="logo-abbr" src="{{ asset('images/logo.png') }}" alt="">
    <span class="brand-title">Peminjaman Alat</span>
    </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!-- Nav header end -->

        <!-- Header start -->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">

                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-user"></i></span>
                                            <div class="media-body">
                                                <a href="#"><p><strong>Martin</strong> has added a <strong>customer</strong> Successfully</p></a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-shopping-cart"></i></span>
                                            <div class="media-body">
                                                <a href="#"><p><strong>Jennifer</strong> purchased Light Dashboard 2.0.</p></a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="danger"><i class="ti-bookmark"></i></span>
                                            <div class="media-body">
                                                <a href="#"><p><strong>Robin</strong> marked a <strong>ticket</strong> as unsolved.</p></a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-heart"></i></span>
                                            <div class="media-body">
                                                <a href="#"><p><strong>David</strong> purchased Light Dashboard 1.0.</p></a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-image"></i></span>
                                            <div class="media-body">
                                                <a href="#"><p><strong>James.</strong> has added a <strong>customer</strong> Successfully</p></a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                    </ul>
                                    <a class="all-notification" href="#">See all notifications <i class="ti-arrow-right"></i></a>
                                </div>
                            </li>
<li class="nav-item dropdown header-profile">
    <a class="nav-link" href="#" role="button" data-toggle="dropdown">
        <i class="mdi mdi-account"></i> {{ Auth::user()->name }}
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="icon-key"></i> Logout
            </button>
        </form>
    </div>
</li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Header end -->

        <!-- Sidebar start -->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            @auth
                @if(Auth::user()->role == 'admin')
                    @include('partials.sidebar_admin')
                @elseif(Auth::user()->role == 'petugas')
                    @include('partials.sidebar_petugas')
                @elseif(Auth::user()->role == 'peminjam')
                    @include('partials.sidebar_peminjam')
                @endif
            @endauth
        </ul>
    </div>
</div>
<!-- Sidebar end -->
        <!-- Content body start -->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
<div class="welcome-text">
    @auth
        <h4>Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</h4>
        <p class="mb-0">
            Anda login sebagai 
            <strong>
                @if(Auth::user()->role == 'admin')
                    Administrator
                @elseif(Auth::user()->role == 'petugas')
                    Petugas
                @else
                    Peminjam
                @endif
            </strong>
        </p>
    @else
        <h4>Hi, welcome back!</h4>
        <p class="mb-0">Your business dashboard template</p>
    @endauth
</div>
<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ 
                Auth::check() ? 
                    (Auth::user()->role == 'admin' ? route('admin.dashboard') : 
                    (Auth::user()->role == 'petugas' ? route('petugas.dashboard') : route('peminjam.dashboard'))) 
                    : url('/') 
            }}">
                <i class="ti-dashboard"></i> Dashboard
            </a>
        </li>
        @php
            $segments = request()->segments();
            $url = '';
        @endphp
        @foreach($segments as $key => $segment)
            @php
                $url .= '/' . $segment;
                $title = ucfirst(str_replace(['-', '_'], ' ', $segment));
            @endphp
            @if(!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ url($url) }}">{{ $title }}</a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            @endif
        @endforeach
    </ol>
</div>
                </div>

                @yield('content')

            </div>
        </div>
        <!-- Content body end -->

        <!-- Footer start -->
        <div class="footer">
            <div class="copyright">
                
            </div>
        </div>
        <!-- Footer end -->

    </div>
    <!-- Main wrapper end -->

    <!-- Scripts -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('vendor/morris/morris.min.js') }}"></script>
    <script src="{{ asset('vendor/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/gaugeJS/dist/gauge.min.js') }}"></script>
    <script src="{{ asset('vendor/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('vendor/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>
</body>

</html>