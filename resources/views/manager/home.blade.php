@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - داشبورد' }} </title>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('manager/assets/css/farsi-calendar.css') }}" />
@endsection

@section('content')
    <div class="page-heading">
        <h3>به سامانه مدیریت <b class="text-primary">{{ config('app.name') }}</b> خوش آمدید</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="dashboard-icon">
                                            <i class="bi bi-layout-text-sidebar-reverse bg-warning text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">تعداد کل هدیه ها</h6>
                                        <h6 class="font-extrabold mb-0">{{ $gift ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="dashboard-icon">
                                            <i class="bi bi-journal-richtext bg-info text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">تعداد کل درخواست ها</h6>
                                        <h6 class="font-extrabold mb-0">{{ $request ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="dashboard-icon">
                                            <i class="bi bi-card-checklist bg-primary text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">تعداد کل کاربران</h6>
                                        <h6 class="font-extrabold mb-0">{{ $users ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="dashboard-icon">
                                            <i class="bi bi-person-circle bg-success text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">#{{ \Illuminate\Support\Facades\Auth::user()->id }}-{{ \Illuminate\Support\Facades\Auth::user()->name }}</h6>
                                        <h6 class="font-extrabold mb-0">{{ \Illuminate\Support\Facades\Auth::user()->tell }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--  main  --}}
            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>آخرین درخواست های ارسال شده</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                        <tr>
                                            <th>شماره</th>
                                            <th>هدیه</th>
                                            <th>آدرس</th>
                                            <th>موبایل</th>
                                            <th>تاریخ ثبت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($gift_request) && count($gift_request))
                                                @foreach($gift_request as $item)
                                                    <tr class="text-center">
                                                        <td><a href="{{ url(\App\Http\Controllers\HomeController::fetch_manager_pre_url() . "/gift_request/".$item['id']."/edit") }}">#{{ $item['id'] }}</a></td>
                                                        <td><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() . '/gift/' . $item['gift_id'] . '/edit') }}">#{{ $item['gift_id'] }}-{{ $item->gift->title ?? '' }}</a></td>
                                                        <td>{{ $item['url'] }}</td>
                                                        <td>{{ $item['mobile'] }}</td>
                                                        <td>{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['created_at']) }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="text-center">
                                                    <td colspan="4">
                                                        <p>در حال حاضر درخواستی ثبت نشده</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--  /main  --}}

            {{--  side  --}}
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>تقویم</h4>
                    </div>
                    <div class="card-content pb-4 p-1">
                        <span class="fc-calendar"></span>
                    </div>
                </div>
            </div>
            {{--  /side  --}}
        </section>
    </div>
@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('manager/assets/js/farsi-calendar.js') }}"></script>
    <script>
        $(document).ready(function () {
            InitCalendar($(".fc-calendar"));
        });
    </script>
@endsection
