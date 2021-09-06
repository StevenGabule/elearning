<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('custom_css')
    <style>
        body {
            color: white;
        }
    </style>
</head>
<body>
<div id="app">
<nav class="admin navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="admin navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">WinLoop Admin</a>
    <button class="admin navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse"
            data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="admin navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <img src="{{Auth::user()->avatar != null ? asset('storage/uploads/users') . '/' . Auth::user()->avatar : asset('images/backend/admin-cover.jpg')}}" style="width: 34px;height: 34px;object-fit: cover" alt="" class="rounded-circle"> {{ Auth::user()->first_name . ' ' .  Auth::user()->last_name }}
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light pl-0 sidebar collapse"  style="background: #222 !important;">
            <div class="sidebar-sticky">
                <ul class="nav flex-column" style="font-size: 12px">
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{ route('admin.dashboard.index') }}" style="font-weight: 500">
                            <i class="bi-speedometer mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.student')}}" style="font-weight: 500">
                            <i class="bi-person mr-3"></i> Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.course')}}" style="font-weight: 500">
                            <i class="bi-camera-reels mr-3"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.instructor')}}" style="font-weight: 500">
                            <i class="bi-people mr-3"></i> Instructors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.transaction')}}" style="font-weight: 500">
                            <i class="bi-newspaper mr-3"></i> Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.payment')}}" style="font-weight: 500">
                            <i class="bi-newspaper mr-3"></i> Payments
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.enrollment')}}" style="font-weight: 500">
                            <i class="bi-person-lines-fill mr-3"></i> Enrollments
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.payout')}}" style="font-weight: 500">
                            <i class="bi-wallet2 mr-3"></i> Payouts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.refund')}}" style="font-weight: 500">
                            <i class="bi-tablet-landscape mr-3"></i>  Refunds
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{route('admin.dashboard.categories')}}" style="font-weight: 500">
                            <i class="bi-tablet-landscape mr-3"></i>  Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50 navlink-sidebar" href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bi-box-arrow-left mr-3"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>
    </div>
</div>
</div>
@stack('custom_script')
</body>
</html>
