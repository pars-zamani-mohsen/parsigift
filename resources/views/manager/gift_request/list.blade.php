@extends('manager.base-forms.list')

@section('table')
    <table class="table table-hover mb-0">
        <thead>
        <tr>
            <th>ID</th>
            <th>منبع</th>
            <th>شماره تلفن همراه</th>
            <th>هدیه</th>
            <th>آخرین ویرایش</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @php #$login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
        @if(count($all))
            @foreach ($all as $key => $item)
                <tr>
                    <td>#{{ $item['id'] }}</td>
                    <td>{{ $item['url'] }}</td>
                    <td>{{ $item['mobile'] }}</td>
                    <td><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() . '/gift/' . $item['gift_id'] . '/edit') }}">#{{ $item['gift_id'] }}-{{ $item->gift->title ?? '' }}</a></td>
                    <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['updated_at']) }}</td>
                    <td>
                        <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id'] . '/history') }}" title="تاریخجه"> <i class="bi bi-clock-history text-info"></i> </a>
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
