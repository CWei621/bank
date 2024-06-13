<!DOCTYPE html>
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
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #f9f9f9;
        }

        /* Container Styles */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Card Styles */
        .card {
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            border-radius: 0.25rem;
        }

        .card-header {
            background-color: #333;
            color: #fff;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #333;
        }

        .card-body {
            padding: 1rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Other Styles */
        .text-gray-700 {
            color: #333;
        }

        .text-gray-500 {
            color: #666;
        }

        .bg-gray-200 {
            background-color: #f7f7f7;
        }
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #f9f9f9;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #333;
            padding: 1rem;
            border-bottom: 1px solid #333;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }

        .nav-link {
            color: #fff;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #666;
        }

        .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #ffdf00;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Other Styles */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .mt-2 {
            margin-top: 1.5rem;
        }

        .py-2 {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .pt-2 {
            padding-top: 2.5rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .text-6xl {
            font-size: 3rem;
        }

        .text-5xl {
            font-size: 2.5rem;
        }

        .text-gray-700 {
            color: #333;
        }

        .text-gray-500 {
            color: #666;
        }

        .text-gray-800 {
            color: #333;
        }

        .bg-gray-200 {
            background-color: #f7f7f7;
        }

        .bg-red-700 {
            background-color: #ff0000;
        }

        .bg-green-500 {
            background-color: #008000;
        }

        .function-btn {
            display: flex;
            flex-direction: row;
            justify-content: center;

            a {
                margin: 1rem;
            }
        }

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/bank') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @if(Auth::check())
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('bank') ? 'active' : '' }}" href="{{ url('/bank') }}">
                                Account Detail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('bank/balance') ? 'active' : '' }}" href="{{ url('/bank/balance') }}">
                                Add Balance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('bank/detail') ? 'active' : '' }}" href="{{ url('/bank/detail') }}">
                                Transaction Detail
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
