<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="short icon" href="{{ asset('images/frontend/logo.png') }}">
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
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md shadow-sm navbar-darker bg-darker">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdownCategory"
                           class="nav-link navbar-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ __('Categories') }}
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdownCategory">
                            @forelse($categories as $category)
                                <a class="dropdown-item" href="{{ route('category.index', ['slug' => $category->slug]) }}">
                                    {{ __($category->name) }}
                                </a>
                            @empty
                                <a class="dropdown-item" href="javascript:void(0)">
                                    {{ __('No category available') }}
                                </a>
                            @endforelse

                        </div>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link navbar-link" href="{{ route('instructor.index') }}" style="">Instructor</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link navbar-link" href="{{ route('student.course') }}" style="">My Learning</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navbar-link" href="{{ route('cart.index') }}" style="">My Cart</a>
                        </li>
                     @endauth
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item mr-2">
                            <a class="nav-link btn btn-light button-danger-outline rounded-0 px-4"
                               style="font-weight: 500"
                               href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link btn btn-light button-danger rounded-0 px-4"
                                   style="font-weight: 500"
                                   href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle"
                               href="#" role="button" style="color: #fff;"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img
                                    src="{{Auth::user()->avatar != null ? asset('storage/uploads/users') . '/' . Auth::user()->avatar : asset('images/backend/admin-cover.jpg')}}" style="width: 34px;height: 34px;object-fit: cover" alt=""
                                    class="rounded-circle mr-2">
                                <span class="text-capitalize">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->user_type != 2)
                                    <a class="dropdown-item" href="{{ route('course.index') }}">
                                        {{ __('Courses') }}
                                    </a>
                                @endif

                                <a class="dropdown-item" href="{{ route('student.course') }}">
                                    {{ __('My Learning') }}
                                </a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="container text-center text-white small mt-5" style="font-weight: 500">
        <p>All right reserved - In Partial Fulfillment of the Subject <strong>Client/Server Application Design</strong> - Students:
            <a href="https://www.facebook.com/BLACKSTARWOLVES/"
               class="btn-link"><strong>John Paul L. Gabule</strong></a> and  <a href="https://www.facebook.com/bojie88"
                                                                                 class="btn-link"><strong>Rheina Merci Ong</strong></a>  | Prof: <strong>Mrs. Aurora Cindy Balabat, Ph.d</strong>
        </p>
    </footer>
</div>

@stack('custom_scripts')
</body>
</html>
