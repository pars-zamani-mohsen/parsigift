@extends('manager.base-forms.list')
@section('head')
@endsection
@section('table')
    <table class="table table-hover mb-0">
        <thead>
        <tr>
            <th>ID</th>
            <th>عنوان</th>
            <th>مدل</th>
            <th>شماره رکورد</th>
            <th>کاربر</th>
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
                    <td><a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id']) }}">@if($item['log_type'] == 'create') ایجاد @elseif($item['log_type'] == 'update') ویرایش @else حذف @endif</a></td>
                    <td>{{ $item['model'] }}</td>
                    <td>#{{ $item['subject_id'] }}</td>
                    <td>{{ $item['user']['name'] }}</td>
                    <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsiDatetime($item['updated_at']) }}</td>
                    <td>
                        <a href="{{ url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $modulename['en'] . '/' . $item['id']) }}" title="مشاهده "> <i class="bi bi-eye"></i> </a>
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
