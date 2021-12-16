@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - داشبورد' }} </title>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('manager/assets/css/farsi-calendar.css') }}" />
@endsection

@section('content')
    @php $login_user = \Illuminate\Support\Facades\Auth::user(); @endphp

    <div class="page-heading">
        <h3>
            <span class="text-primary">{{ $login_user->name }} عزیز</span>
            <span>به سامانه مدیریت</span>
            <b class="text-primary">{{ config('app.name') }}</b>
            <span> خوش آمدید</span>
        </h3>
    </div>
    <div class="page-content">
        <section class="row">
            @if($login_user->role == "admin")
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
                                            <h6 class="text-muted font-semibold">تعداد کل هدیه ها </h6>
                                            <h6 class="font-extrabold mb-0">{{ $dailyGift ?? 0 }}</h6>
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
                                            <h6 class="text-muted font-semibold">تعداد جستجو های در حال انتظار امروز</h6>
                                            <h6 class="font-extrabold mb-0">{{ $pending_dailyQuery ?? 0 }}</h6>
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
                                            <h6 class="text-muted font-semibold">#{{ $login_user->id }}-{{ $login_user->name }}</h6>
                                            <h6 class="font-extrabold mb-0">{{ $login_user->tell }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{--  main  --}}
            @if($login_user->role != "admin")
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>عبارت هایی که امروز باید در گوگل جستجو کنم</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                        <tr>
{{--                                            <th>شماره</th>--}}
{{--                                            <th>کاربر</th>--}}
                                            <th>چیزی که باید جستجو کنم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($dailyQuery) && count($dailyQuery))
                                                @foreach($dailyQuery as $item)
                                                    <tr>
                                                    <!--<td>#{{ $item['id'] }}</td>-->
                                                    <!--<td><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() . '/user/' . $item['user_id'] . '/edit') }}">#{{ $item['user_id'] }}-{{ $item->user->name ?? '' }}</a></td>-->
                                                        <td>
                                                            <div><a href="#"><!--#{{ $item['query_id'] }}-->{{ $item->_query->title ?? '' }}</a></div>
                                                            <div dir="ltr">{{ urldecode($item->_query->url) ?? '' }}</div>
                                                            <div dir="rtl" class="@if($item['status']) text-success @else text-danger @endif">
                                                                {{ ($item['status']) ? 'ثبت شد' : 'هنوز ثبت نشده' }}
                                                                @if(!$item['status'])<a class="btn btn-sm btn-light-danger" href="{{ url('/_manager/changeQuery/' . $item['id']) }}">تغییر عبارت</a>@endif
                                                            </div>
                                                        </td>
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
            @endif
            {{--  /main  --}}

            {{--  side  --}}

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>گزارش جستجوی های من در روزهای گذشته</h4>
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
    <script src="{{ asset('/manager/assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('manager/assets/js/farsi-calendar.js') }}"></script>
    <script>
        $(document).ready(function () {
            InitCalendar($(".fc-calendar"));
        });
    </script>
@endsection
