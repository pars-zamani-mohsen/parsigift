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
    <?php
        #$login_user = \Illuminate\Support\Facades\Auth::user();
        $s_modulename = (isset($s_modulename)) ? $s_modulename : null;
    ?>

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
                                            <h4 class="card-title">{{ $modulename['fa'] }} @if($s_modulename) ({{ $s_modulename }}) @endif</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    <div class="row justify-content-end">
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 text-end">
                                            <select name="modulenamelist" id="modulenamelist" class="form-select" onchange="redirect()">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($modulenamelist as $key => $item)
                                                    <option value="{{ $key }}" @if($s_modulename == $key) selected @endif>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <!-- table hover -->
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>عنوان</th>
                                        <th>تاریخ ثبت</th>
                                        <th>تاریخ حذف</th>
                                        <th>ایجاد شده توسط</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php #$login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    @if(count($all))
                                        @foreach ($all as $key => $item)
                                            <tr>
                                                <td>#{{ $item['id'] }}</td>
                                                <td>{{ $item['title'] ?? $item['name'] }}</td>
                                                <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['created_at']) }}</td>
                                                <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['deleted_at']) }}</td>
                                                <td>#{{ $item['publisher']['id'] }}-{{ $item['publisher']['name'] }}</td>
                                                <td>
                                                    {{--<a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $s_modulename . '/' . $item['id'] . '/history') }}" title="تاریخجه"> <i class="bi bi-clock-history text-info"></i> </a>--}}
                                                    <a class="_deactive" href="#"> <i class="bi bi-arrow-repeat text-warning" data-url="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $s_modulename . '/' . $item['id'] . '/restore') }}" title="بازیابی"></i> </a>
                                                    <a class="_delete" href="#"> <i class="bi bi-trash text-danger" data-url="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $s_modulename . '/' . $item['id'] . '/delete') }}" title="حذف"></i> </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <p class="p-2">سطل بازیابی خالی است!</p>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @if(count($all))
                                    <div class="my-paginate col-12 text-center @if(count($all->render()->elements[0]) > 1) p-3 @endif">
                                        {{ $all->render() }}
                                    </div>
                                @endif
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
    <script src="{{ asset('manager/assets/js/list.js')}}"></script>
    <script>
        function redirect() {
            let instance = document.getElementById('modulenamelist');
            window.location.replace('{{ url('/_manager/recyclebin/') }}/' + instance.value);
        }
    </script>
@endsection
