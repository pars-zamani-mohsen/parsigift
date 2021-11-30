@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - ' . $modulename['fa'] }} </title>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('manager/assets/css/widgets/chat.css') }}">
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
                            <li class="breadcrumb-item"><a href="{{ url('/' . App\Http\Controllers\HomeController::fetch_manager_pre_url()) . '/' . $modulename['en'] }}">{{ $modulename['fa'] }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تاریخچه</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    <?php #$login_user = \Illuminate\Support\Facades\Auth::user() ?>
        <section class="section">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="row justify-content-start">
                                        <div class="col">
                                            <h4 class="card-title">فهرست تغییر های رکورد</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="row justify-content-end">
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
                        <div class="card-body pt-4 bg-grey">
                            <div class="chat-content">
                                @foreach($all as $key => $item)
                                    <div class="chat">
                                        <div class="chat-body">
                                            <div class="chat-message">
                                                <p>
                                                    <span>رکورد </span>
                                                    <span class="text-primary">#{{ $item['id'] }}</span>
                                                    @if($item['user'])
                                                    <span> توسط </span>
                                                    <span class="text-dark">{{ $item['user'] }}</span>
                                                    @endif
                                                    <span>{{ $item['message']}}</span>
                                                </p>
                                                @if ($item['data'] && count($item['data']))
                                                    <ul>
                                                        @foreach ($item['data'] as $field)
                                                            <li>
                                                                <p class="text-start fs-7">
                                                                    @if($field == 'fields.deleted_at')
                                                                        <span>بازیابی شد.</span>
                                                                    @else
                                                                        <span class="text-black">{{ $field }}</span>
                                                                        <span>تغییر یافت.</span>
                                                                    @endif
                                                                </p>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                <p><small class="chat-datetime" dir="ltr">{{ $item['datetime'] }}</small></p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{--<div class="chat chat-left">
                                    <div class="chat-body">
                                        <div class="chat-message">That"s great! I like it so much :)</div>
                                    </div>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type='text/javascript' defer>

    </script>
@endsection
