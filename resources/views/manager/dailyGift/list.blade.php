@extends('manager.base-forms.list')

@section('table')
    @php $login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
    @if($login_user->role == "admin")
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>کاربر</th>
                <th>عنوان هدیه</th>
                <th>مبلغ هدیه (تومان)</th>
                <th>تاریخ ثبت</th>
            </tr>
            </thead>
            <tbody>
            @if(count($all))
                @foreach ($all as $key => $item)
                    <tr>
                        <td>#{{ $item['id'] }}</td>
                        <td>@if($item['user']['id'])<a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/user/' . $item['user']['id'] .'/edit') }}" target="_blank">#{{ $item['user']['id'] }}-{{ $item['user']['name'] }}</a>@endif</td>
                        <td>{{ $item['title'] }}</td>
                        <td class="text-success">{{ number_format($item['amount']) }}</td>
                        <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['updated_at']) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        <p class="p-2">رکوردی برای {{ $modulename['fa'] }} ثبت نشده!</p>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    @else
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <!--<th>ID</th>-->
                <!--<th>کاربر</th>-->
                <th>عنوان هدیه</th>
                <th>مبلغ هدیه (تومان)</th>
                <th>تاریخ ثبت</th>
            </tr>
            </thead>
            <tbody>
            @if(count($all))
                @foreach ($all as $key => $item)
                    <tr>
                        <!--<td>#{{ $item['id'] }}</td>-->
                        <!--<td>@if($item['user']['id'])<a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/user/' . $item['user']['id'] .'/edit') }}" target="_blank">#{{ $item['user']['id'] }}-{{ $item['user']['name'] }}</a>@endif</td>-->
                        <td>{{ $item['title'] }}</td>
                        <td class="text-success">{{ App\AdditionalClasses\Date::convertEnglishNumToPersian(number_format($item['amount'])) }}</td>
                        <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsi($item['updated_at']) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        <p class="p-2">رکوردی برای {{ $modulename['fa'] }} ثبت نشده!</p>
                    </td>
                </tr>
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td class="p-3" colspan="3">
                        <div class=" alert alert-danger">
                                <span> جایزه شما تا این لحظه:  <b class="bold text-dark">{{ App\AdditionalClasses\Date::convertEnglishNumToPersian(number_format($totalGIft)) }}</b> تومان </span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif
    <div class="my-paginate col-12 text-center @if(count($all->render()->elements[0]) > 1) p-3 @endif">
        {{ $all->render() }}
    </div>
@endsection
@section('script')
    <script src="{{ asset('manager/assets/js/list.js')}}"></script>
@endsection
