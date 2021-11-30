@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - ' . $modulename['fa'] }} </title>
@endsection

@section('head')

@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted invisible">Multiple form layout you can use</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/' . App\Http\Controllers\HomeController::fetch_manager_pre_url()) }}">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $modulename['fa'] }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    <?php #$login_user = \Illuminate\Support\Facades\Auth::user() ?>

        <!-- Hoverable rows start -->
        <section class="section">
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="row justify-content-start">
                                        <div class="col">
                                            <h4 class="card-title">{{ $modulename['fa'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="row justify-content-end">
                                        @if(isset($import) && $import)
                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/import/excel') }}" class="btn btn-outline-info w-100"><i class="bi bi-upload bi-line-height"></i> ورود اطلاعات </a>
                                            </div>
                                        @endif
                                        @if(isset($export) && $export)
                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <button type="button" class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#inlineForm"><i class="bi bi-download bi-line-height"></i> خروجی اکسل </button>
                                            </div>
                                        @endif

                                        @if(isset($is_related_list) && $is_related_list)
                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en']) }}" class="btn btn-primary w-100"><i class="bi bi-arrow-left-circle bi-line-height"></i> بازگشت </a>
                                            </div>
                                        @else
                                            @if(!isset($onlylist) || !$onlylist)
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                    <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/create') }}" class="btn btn-primary w-100"><i class="bi bi-plus-square-dotted bi-line-height"></i> ایجاد </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                @yield('table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hoverable rows end -->
    </div>
@endsection

@section('script')
    <script type='text/javascript' defer>

    </script>
@endsection
