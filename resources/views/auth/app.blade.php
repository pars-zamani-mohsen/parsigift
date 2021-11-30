<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/logo/parsicanada-farsi.png') }}">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('manager/assets/css/bootstrap.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/css/app.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/css/pages/auth.rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/toastify/toastify.css') }}">

    <script src="{{ asset('manager/assets/vendors/fontawesome/all.min.js') }}"></script>
</head>

<body>
<div id="auth">
    <div class="row h-100">
        <div class="col-xl-5 col-lg-7 col-12">
            <div id="auth-left">
                @yield('content')
            </div>
        </div>
        <div class="col-xl-7 col-lg-5 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>
</div>

<script src="{{ asset('manager/assets/vendors/toastify/toastify.js') }}"></script>

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
