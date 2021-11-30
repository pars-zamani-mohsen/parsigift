<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @yield('_title')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('meta')

    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo/parsicanada-farsi.png') }}">

    <link rel="stylesheet" href="{{ asset('manager/assets/css/bootstrap.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/css/app.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/toastify/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    @yield('head')
</head>

<body>
<div id="app">
    @include('layouts.manager.sidebar')
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        @yield('content')

        <div class="clearfix"></div>
        @include('layouts.manager.footer')
    </div>
</div>


<script src="{{ asset('manager/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('manager/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('manager/assets/vendors/toastify/toastify.js') }}"></script>
<script src="{{ asset('manager/assets/js/main.js') }}"></script>
<script src="{{ asset('manager/assets/js/custom.js') }}"></script>
<script src="{{ asset('manager/assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
@yield('script')


@if (count($errors) > 0)
    @foreach ($errors->all() as $key => $error)
        <script>
            Toastify({
                text: "{!! $error !!}",
                duration: 8000,
                close:true,
                gravity:"top",
                position: "center",
                backgroundColor: "#dc3545",
            }).showToast();
        </script>
    @endforeach
@endif

@if (Session::has('message'))
    <script>
        Toastify({
            text: "{{ Session::get('message') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#198754",
        }).showToast();
    </script>
@endif

@if (Session::has('alert'))
    <script>
        Toastify({
            text: "{{ Session::get('alert') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#ffc107",
        }).showToast();
    </script>
@endif

@if (Session::has('error'))
    <script>
        Toastify({
            text: "{{ Session::get('error') }}",
            duration: 8000,
            close:true,
            gravity:"top",
            position: "center",
            backgroundColor: "#dc3545",
        }).showToast();
    </script>
@endif
</body>
</html>
