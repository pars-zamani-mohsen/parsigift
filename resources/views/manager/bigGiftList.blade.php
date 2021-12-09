@extends('manager.base-forms.list')

@section('table')
    @php $login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <!--<th>ID</th>-->
                <th>کاربر</th>
                <th>تاریخ ثبت</th>
            </tr>
            </thead>
            <tbody>
            @if(count($all))
                @foreach ($all as $key => $item)
                    <tr>
                        <!--<td>#{{ $item['id'] }}</td>-->
                        <td>{{ $item->user['name'] }}</td>
                        <td dir="ltr" class="text-start">{{ App\AdditionalClasses\Date::timestampToShamsi($item['created_at']) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">
                        <p class="p-2">رکوردی  ثبت نشده!</p>
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
