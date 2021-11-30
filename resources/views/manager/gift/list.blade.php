@extends('manager.base-forms.list')

@section('table')
    <table class="table table-hover mb-0">
        <thead>
        <tr>
            <th>ID</th>
            <th>عنوان</th>
            <th>مبلغ</th>
            <th>تعداد</th>
            <th>وضعیت</th>
            <th>آخرین ویرایش</th>
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
                    <td><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] .'/edit') }}">{{ \Illuminate\Support\Str::limit(strip_tags($item['title']), 100) }}</a></td>
                    <td>{{ $item['amount'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td class="@if($item['active']) text-success @else text-danger @endif">
                        {{ ($item['active']) ? 'فعال' : 'غیر فعال' }}
                    </td>
                    <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['updated_at']) }}</td>
                    <td>#{{ $item['publisher']['id'] }}-{{ $item['publisher']['name'] }}</td>
                    <td>
                        <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] . '/edit') }}" title="ویرایش "> <i class="bi bi-pencil-square"></i> </a>
                        <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] . '/history') }}" title="تاریخجه"> <i class="bi bi-clock-history text-info"></i> </a>
                        <a class="_deactive" href="#"> <i class="bi bi-lightbulb-off text-warning" data-url="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] . '/activation') }}" title="فعال/غیر فعال"></i> </a>
                        <a class="_delete" href="#"> <i class="bi bi-trash text-danger" data-url="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] . '/delete') }}" title="حذف"></i> </a>
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
@endsection
@section('script')
    <script src="{{ asset('manager/assets/js/list.js')}}"></script>
@endsection
