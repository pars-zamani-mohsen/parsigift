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
{{--                    <h3>{{ $title }}</h3>--}}
                    <p class="text-subtitle text-muted invisible">Multiple form layout you can use</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                   href="{{ url('/' . App\Http\Controllers\HomeController::fetch_manager_pre_url()) }}">داشبورد</a>
                           </li>
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
                                        @if(isset($search) && $search)
                                            <div class="col-xxl-1 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end mb-1">
                                                <a href="#" class="btn btn-light-secondary w-100" data-bs-toggle="modal" data-bs-target="#search"><i class="bi bi-search bi-line-height"></i> </a>
                                            </div>
                                        @endif
                                        @if(isset($import) && $import)
                                            <div
                                               class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/import/excel') }}"
                                                  class="btn btn-outline-info w-100"><i
                                                       class="bi bi-upload bi-line-height"></i> ورود اطلاعات </a>
                                            </div>
                                        @endif
                                        @if(isset($export) && $export)
                                            <div
                                               class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <button type="button" class="btn btn-outline-info w-100"
                                                       data-bs-toggle="modal" data-bs-target="#inlineForm"><i
                                                       class="bi bi-download bi-line-height"></i> خروجی اکسل
                                               </button>
                                            </div>
                                        @endif
                                        @if(isset($navigation) && $navigation)
                                            <div
                                                class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $navigation['url']) }}"
                                                   class="btn btn-primary w-100"><i
                                                        class="bi {{ $navigation['icon'] }} bi-line-height"></i> {{ $navigation['title'] }} </a>
                                            </div>
                                        @endif

                                        @if(isset($is_related_list) && $is_related_list)
                                            <div
                                               class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en']) }}"
                                                  class="btn btn-primary w-100"><i
                                                       class="bi bi-arrow-left-circle bi-line-height"></i> بازگشت </a>
                                            </div>
                                        @else
                                            @if(!isset($onlylist) || !$onlylist)
                                                <div
                                                   class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                                    <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/create') }}"
                                                      class="btn btn-primary w-100"><i
                                                           class="bi bi-plus-square-dotted bi-line-height"></i> ایجاد
                                                   </a>
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
    <!-- search form Modal -->
    <div class="modal fade text-left" id="search" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
             role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title white" id="myModalLabel33">جستجو</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
                </div>
                <form action="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/module/search') }}" method="get">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="field">عبارت مورد نظر را در کادر زیر وارد کنید <small class="text-danger">(آیدی، متن، تاریخ و ...)</small></label>
                                    <input type="text" name="field" id="field" class="form-control" value="" autofocus required autocomplete="field">
                                    <small>ستون های مرتبط(مانند کشور، خدمت و ...) را با آیدی جستجو کنید</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status">فقط رکورد های فعال </label>
                                    <input type="checkbox" name="status" id="status" class="form-check-input form-check-info" value="active">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary ml-1">تایید</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type='text/javascript' defer>

    </script>
@endsection

