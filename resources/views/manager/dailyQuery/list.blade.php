@extends('manager.base-forms.list')

@section('table')
    @php $login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
    @if($login_user->role == "admin")
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>کاربر</th>
                <th>کوئری</th>
                <th>URL</th>
                <th>وضعیت</th>
                <th>تاریخ ثبت</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @if(count($all))
                @foreach ($all as $key => $item)
                    <tr>
                        <td>#{{ $item['id'] }}</td>
                        <td>@if($item['user']['id'])<a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/user/' . $item['user']['id'] .'/edit') }}" target="_blank">#{{ $item['user']['id'] }}-{{ $item['user']['name'] }}</a>@endif</td>
                        <td>@if($item['_query']['id'])<a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/query/' . $item['_query']['id'] .'/edit') }}" target="_blank">#{{ $item['_query']['id'] }}-{{ $item['_query']['title'] }}</a>@endif</td>
                        <td dir="ltr">@if($item['_query']['id'])<a href="{{ $item['_query']['url'] }}" target="_blank">{{ urldecode($item['_query']['url']) }}</a>@endif</td>
                        <td class="@if($item['status']) text-success @else text-danger @endif">
                            {{ ($item['status']) ? 'ثبت شد' : 'هنوز ثبت نشده' }}
                        </td>
                        <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['updated_at']) }}</td>
                        <td>
                            @php $title = $item['_query']['title']; @endphp
                            <a class="" href="#" onclick="copyTextFunction('{{ $title }}')"> <i class="bi bi-files text-danger" title="کپی متن"></i> </a>
                        </td>
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
        <div class="my-paginate col-12 text-center @if(count($all->render()->elements[0]) > 1) p-3 @endif">
            {{ $all->render() }}
        </div>
    @else
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th></th>
                <th>چیزی که باید جستجو کنم</th>
                <th>وضعیت</th>
            </tr>
            </thead>
            <tbody>
            @php $i = 0; @endphp
            @if(count($all))
                @foreach ($all as $key => $item)
                    <tr>
                        <td><!-- #{{ $item['id'] }}--></td>
                        <td>@if($item['_query']['id'])<a href="#"><!--#{{ $item['_query']['id'] }}-->{{ $item['_query']['title'] }}</a>@endif</td>
                        <td class="@if($item['status']) text-success @else text-danger @endif">
                            {{ ($item['status']) ? 'ثبت شد' : 'هنوز ثبت نشده' }}
                        </td>
                    </tr>
                    @php if(!$item['status']) ++$i; @endphp
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
                            @if ($i)
                                <span>شما باید </span>
                                <span class="text-dark2 text-decoration-underline"><b class="text-dark">{{ App\AdditionalClasses\Date::convertEnglishNumToPersian($i) }}</b></span>
                                <span> تا کلمه دیگه برای امروز جستجو کنی تا بتونی شانس خودتو برای جایزه <b class="bold text-dark">{{ App\AdditionalClasses\Date::convertEnglishNumToPersian(number_format(\App\DailyGift::$dailySpecialGiftAmount)) }}</b> تومانی امتحان کنی!</span>
                            @else
                                <span> مبلغ <b class="text-dark">{{ App\AdditionalClasses\Date::convertEnglishNumToPersian(number_format(\App\DailyGift::$dailyGiftAmount)) }}</b> تومان هدیه برای شما ثبت شد.</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
@section('script')
    <script src="{{ asset('manager/assets/js/list.js')}}"></script>
    <script>
        function copyTextFunction(copyText) {
            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText);

            /* Alert the copied text */
            Toastify({
                text: ' متن "' + copyText + '" کپی شد. ',
                duration: 3000,
                close:true,
                gravity:"top",
                position: "center",
                backgroundColor: "#4fbe87",
            }).showToast();
        }
    </script>
@endsection
