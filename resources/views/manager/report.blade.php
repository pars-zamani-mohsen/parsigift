@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - گزارش' }} </title>
@endsection

@section('head')
    <link type="text/css" rel="stylesheet" href="{{ asset('/manager/assets/css/kamadatepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('manager/assets/css/farsi-calendar.css') }}" />
@endsection
@section('content')
    @if($current_user->role == 'user')
        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>گزارش جستجوی های من در روزهای گذشته</h4>
                        </div>
                        <div class="card-content pb-4 p-1">
                            <div class="row">
                                <div class="col-12">
                                    <span class="fc-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="page-heading">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h3> گزارش های پارس گیفت </h3>
                </div>
                <div class="col-12 col-md-4 float-end">
                    <div class="form-group">
                        <label for="calendar">انتخاب تاریخ </label>
                        <input type="text" name="calendar" id="calendar" class="form-control" value="{{ App\AdditionalClasses\Date::timestampToShamsiEng(strtotime($date ?? time())) }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-fill bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد کل کاربران(غیر ادمین)</h6>
                                            <h6 class="font-extrabold mb-0">{{ $all_user['count'] ?? 0 }} (%{{ $all_user['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-check-fill bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد کل کاربران فعال (غیر ادمین)</h6>
                                            <h6 class="font-extrabold mb-0">{{ $all_user_active['count'] ?? 0 }} (%{{ $all_user_active['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-x-fill bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد کل کاربران جدید غیرفعال (غیرادمین)</h6>
                                            <h6 class="font-extrabold mb-0">{{ $all_user_deactive['count'] ?? 0 }} (%{{ $all_user_deactive['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-list-stars bg-primary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد کل کوئری ها </h6><span class="fs-7">(در بازه انتخاب شده)</span>
                                            <h6 class="font-extrabold mb-0">{{ $today_query['count'] ?? 0 }} (%{{ $today_query['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-list-task bg-danger text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد جستجو های در حال انتظار </h6><span class="fs-7">(در بازه انتخاب شده)</span>
                                            <h6 class="font-extrabold mb-0">{{ $pending_query['count'] ?? 0 }} (%{{ $pending_query['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-list-check bg-success text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">تعداد جستجو های ثبت شده </h6><span class="fs-7">(در بازه انتخاب شده)</span>
                                            <h6 class="font-extrabold mb-0">{{ $success_query['count'] ?? 0 }} (%{{ $success_query['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-lines-fill bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">
                                                <span>تعداد کل کاربرهای فعال که تسک دریافت کرده اند </span>
                                                <span class="bold text-success invisible">تکمیل شده</span> <span class="fs-7">(در بازه انتخاب شده)</span>
                                                <a class="btn btn-outline-info" href="{{ url('/_manager/report/task/list/all/' . implode(',', $all_users_query_list['value'] ?? array())) }}">مشاهده</a>
                                            </h6>
                                            <h6 class="font-extrabold mb-0">{{ $all_users_query_list['count'] ?? 0 }} (%{{ $all_users_query_list['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-check bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">
                                                <span>تعداد کاربرهایی که  تسک هایشان </span>
                                                <span class="bold text-success">تکمیل شده</span> <span class="fs-7">(در بازه انتخاب شده)</span>
                                                <a class="btn btn-outline-info" href="{{ url('/_manager/report/task/list/success/' . implode(',', $users_success_query_list['value'] ?? array())) }}">مشاهده</a>
                                            </h6>
                                            <h6 class="font-extrabold mb-0">{{ $users_success_query_list['count'] ?? 0 }} (%{{ $users_success_query_list['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="dashboard-icon">
                                                <i class="bi bi-person-x bg-secondary text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">
                                                <span>تعداد کاربرهایی که  تسک هایشان </span>
                                                <span class="text-danger">تکمیل نشده</span> <span class="fs-7">(در بازه انتخاب شده)</span>
                                                <a class="btn btn-outline-info" href="{{ url('/_manager/report/task/list/pending/' . implode(',', $users_pending_query_list['value'] ?? array())) }}">مشاهده</a>
                                            </h6>
                                            <h6 class="font-extrabold mb-0">{{ $users_pending_query_list['count'] ?? 0 }} (%{{ $users_pending_query_list['percent'] ?? 0 }})</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if(count($success_query_list ?? array()))
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>کوئری های <span class="bold text-success">ثبت شده</span> </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-lg">
                                            <thead>
                                            <tr>
                                                <th>ردیف</th>
                                                <th>نام</th>
                                                <th>تعداد</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = 0; @endphp
                                            @foreach($success_query_list as $key => $success_query)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>#{{ $key }}-{{ $success_query[0]['_query']['title'] }}</td>
                                                    <td>{{ count($success_query) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(count($pending_query_list ?? array()))
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>کوئری های <span class="bold text-danger">تکمیل نشده</span> </h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-lg">
                                            <thead>
                                            <tr>
                                                <th>ردیف</th>
                                                <th>نام</th>
                                                <th>تعداد</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i = 0; @endphp
                                            @foreach($pending_query_list as $key => $success_query)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>#{{ $key }}-{{ $success_query[0]['_query']['title'] }}</td>
                                                    <td>{{ count($success_query) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    @endif
@endsection

@section('script')
    <script src="{{ asset('/manager/assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('/manager/assets/js/kamadatepicker.min.js') }}"></script>
    <script src="{{ asset('/manager/assets/js/kamadatepicker.holidays.js') }}"></script>
    <script src="{{ asset('manager/assets/js/farsi-calendar.js') }}"></script>
    <script>
        kamaDatepicker('calendar', {
            nextButtonIcon: "{{ asset('/manager/assets/images/timeir_prev.png') }}"
            , previousButtonIcon: "{{ asset('/manager/assets/images/timeir_next.png') }}"
            , forceFarsiDigits: true
            , markToday: true
            , markHolidays: true
            , highlightSelectedDay: true
            , sync: true
            , pastYearsCount: 0
            , futureYearsCount: 3
            , closeAfterSelect: true
            , swapNextPrev: true
            , holidays: HOLIDAYS // from kamadatepicker.holidays.js
            , disableHolidays: false
            , gotoToday: true
        });

        $(document).ready(function () {
            InitCalendar($(".fc-calendar"));

            $('#calendar').on('change', function () {
                // Simulate an HTTP redirect:
                window.location.replace('/_manager/report/' + (this.value).replaceAll('/', '-'));
            });
        });
    </script>
@endsection
