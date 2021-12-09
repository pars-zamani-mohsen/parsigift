@extends('layouts.manager.app')

@section('_title')
    <title>{{ config('app.name') . ' - گزارش' }} </title>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('manager/assets/css/farsi-calendar.css') }}" />
@endsection

@section('content')
    @php $login_user = \Illuminate\Support\Facades\Auth::user(); @endphp

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
