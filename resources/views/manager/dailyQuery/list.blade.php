@extends('manager.base-forms.list')

@section('table')
    <table class="table table-hover mb-0">
        <thead>
        <tr>
            <th>ID</th>
            <th>کاربر</th>
            <th>کوئری</th>
            <th>وضعیت</th>
            <th>تاریخ ثبت</th>
        </tr>
        </thead>
        <tbody>
        @php #$login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
        @if(count($all))
            @foreach ($all as $key => $item)
                <tr>
                    <td>#{{ $item['id'] }}</td>
                    <td>{{ $item['user_id'] }}</td>
                    <td>{{ $item['query_id'] }}</td>
                    <td class="@if($item['status']) text-success @else text-danger @endif">
                        {{ ($item['status']) ? 'تکمیل شد' : 'تکمیل نشده' }}
                    </td>
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
    <div class="my-paginate col-12 text-center @if(count($all->render()->elements[0]) > 1) p-3 @endif">
        {{ $all->render() }}
    </div>
@endsection
@section('script')
    <script src="{{ asset('manager/assets/js/list.js')}}"></script>
@endsection
