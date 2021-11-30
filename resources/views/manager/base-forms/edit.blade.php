@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - ' . $modulename['fa'] }} </title>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('manager/assets/vendors/choices.js/choices.min.css') }}" />
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">لطفا اطلاعات را با دقت تکمیل کنید</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/') }}">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en']) }}">فهرست {{ $modulename['fa'] }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $modulename['fa'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @yield('form')
    </div>
@endsection

@section('script')
    <script src="{{ asset('manager/assets/vendors/choices.js/choices.min.js') }}"></script>
@endsection
