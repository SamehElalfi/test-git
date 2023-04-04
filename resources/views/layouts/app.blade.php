<?php
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Session as Session;
?>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
</head>
<style>
    @font-face { font-family: DG-Shamael-Black; src: url('{{ asset('fonts/DGShamael-Black.ttf') }}'); }
    @font-face { font-family: Tajawal medium; src: url('{{ asset('fonts/Tajawal-Medium.ttf') }}'); }
    h1, h2, h3, h4, h5, h6, a, span, input, textarea,select, select option, .btn, label, .alert, th{
        font-family: 'DG-Shamael-Black';
        font-weight: 600;
    }
    h6, .btn{
        font-weight: 500;
    }
    .col-xl-2 .expanded{
        overflow: auto;
    }
    /*a:not(.no-class, .image-cate, .image-offer){*/
    /*    transition: width 500ms linear, height 500ms linear;*/
    /*}*/
    /*a:not(.btn, .text-nav, .no-opacity, .no-class, .image-cate, .image-offer):hover{*/
    /*    opacity: 75%;*/
    /*}*/
</style>
<body>
<section class="wrapper-box">
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" dir="rtl">
            <a class="navbar-brand" href="{{ route('home') }}">Yossr</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">إنشاء حساب</a>
                        </li>

                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('quiz.create') }}">طلب إستمارة</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                الحساب
                            </a>
                            <div class="dropdown-menu px-4 text-right" aria-labelledby="navbarDropdown">
                                <h6>اهلا وسهلا, {{ Auth::user()->name }}</h6>
                                <h6 class="text-secondary">
                                    العضوية (
                                    @if(Auth::user()->membership == 0)
                                        عميل
                                    @elseif(Auth::user()->membership == 1)
                                        سائق
                                    @elseif(Auth::user()->membership == 2)
                                        دعم فني
                                    @elseif(Auth::user()->membership == 3)
                                        أدمن
                                    @endif
                                    )
                                </h6>
                                <hr>
                                @if(Auth::user()->membership == 1)
                                    <a class="dropdown-item" href="{{ route('driver.profile.edit') }}">الملف الشخصي</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('profile') }}">الملف الشخصي</a>
                                    <a class="dropdown-item" href="{{ route('user.analytics', Auth::id()) }}">نشاطاتي</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('quizzes') }}">إدارة الطلبات</a>
                                @if(Auth::user()->membership == 1)
                                    <a class="dropdown-item" href="{{ route('driver.analytics', Auth::user()->driver->id) }}">نشاطاتي</a>
                                    <a class="dropdown-item" href="{{ route('driver.panel') }}">لوحة إدارة الرحلات</a>
                                @elseif(Auth::user()->membership == 2)
                                    <a class="dropdown-item" href="{{ route('cs.analytics', Auth::id()) }}">نشاطاتي</a>
                                    <a class="dropdown-item" href="{{ route('admin.panel') }}">لوحة التحكم</a>
                                @elseif(Auth::user()->membership == 3)
                                    <a class="dropdown-item" href="{{ route('admin.panel') }}">إدارة الموقع</a>
                                @endif
                                <br>
                                <a>
                                    <a href="{{ route('logout') }}" class="text-decoration-none"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="fa-sharp fa-solid fa-door-open text-danger"></i>&nbsp;
                                        <span class="font-weight-bold text-danger">تسجيل الخروج</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </a>
                            </div>
                        </li>
                    @endguest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about_us') }}">من نحن</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('home') }}">الرئيسية <span class="sr-only">(current)</span></a>
                        </li>
                </ul>
{{--                <form class="form-inline my-2 my-lg-0">--}}
{{--                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
{{--                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
{{--                </form>--}}
            </div>
        </nav>

        <main class="py-4" style="background: linear-gradient(-60deg, #bdbdbd,#f1f1f1);border-radius: 0">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-center text-white">
            <!-- Grid container -->
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <a class="m-1" href="#!" role="button" style="display: inline-block"><h6 class="text-white">من نحن</h6></a>

                    <a class="m-1" href="#!" role="button" style="display: inline-block"><h6 class="text-white">الحماية و الخصوصية</h6></a>

                    <a class="m-1" href="#!" role="button" style="display: inline-block"><h6 class="text-white">البنود</h6></a>

                    <a class="m-1" href="#!" role="button" style="display: inline-block"><h6 class="text-white">تواصل معنا</h6></a>

                    <a class="m-1" href="#!" role="button" style="display: inline-block"><h6 class="text-white">الرئيسية</h6></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <div class="container p-4 pb-0">
                <!-- Section: Social media -->
                <section class="mb-4">
                    <!-- Facebook -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-facebook-f"></i
                        ></a>

                    <!-- Twitter -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-twitter"></i
                        ></a>

                    <!-- Google -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-google"></i
                        ></a>

                    <!-- Instagram -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-instagram"></i
                        ></a>

                    <!-- Linkedin -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-linkedin-in"></i
                        ></a>

                    <!-- Github -->
                    <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
                    ><i class="fab fa-github"></i
                        ></a>
                </section>
                <!-- Section: Social media -->
            </div>
            <!-- Grid container -->

            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2023 Copyright:
                <a class="text-white" href="#">Yossr</a>
            </div>
            <!-- Copyright -->
        </footer>
    </div>
</section>
<br><br>
</body>
</html>
