@extends('manager.base-forms.edit')
@section('head')
@endsection
@section('form')
    <!-- // Basic multiple Column Form section start -->
    @php #$login_user = \Illuminate\Support\Facades\Auth::user(); @endphp
    <section id="ticket-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content pt-4">
                        {{--@if(isset($This) && $This)
                            <div class="card-header">
                                <small class="">عنوان</small>
                                <h4 class="card-title">{{ $This['title'] }}</h4>
                            </div>
                        @endif--}}
                        <div class="card-body">
                            <form class="form" name="ticket" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="log_type">نوع فعالیت </label>
                                            <input type="text" id="log_type" class="form-control" disabled
                                                   value="{{ $This->log_type }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="model">ماژول </label>
                                            <input type="text" id="model" class="form-control" disabled
                                                   value="{{ $This->model }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="subject_id">شماره رکورد </label>
                                            <input type="text" id="subject_id" class="form-control" disabled
                                                   value="{{ $This->subject_id }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="user_id">کاربر </label>
                                            <input type="text" id="user_id" class="form-control" disabled
                                                   value="{{ (\App\User::getUser($This->user_id))->name }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="content">محتوای رکورد </label>
                                            @php
                                                $data = '';
                                                foreach($This->content as $key => $item){
                                                    if ($key == 'created_at' || $key == 'updated_at' || $key == 'deleted_at')
                                                        $data .= "$key : ".App\AdditionalClasses\Date::timestampToShamsiDatetime($item)." \n";
                                                    elseif($key == 'created_by')
                                                        $data .= "$key : " . (\App\User::getUser($This->user_id))->name . " \n";
                                                    else
                                                        $data .= "$key : $item \n";
                                                }
                                            @endphp
                                            <textarea class="form-control" id="content" rows="10" disabled>{{ $data }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="created_at">زمان ثبت </label>
                                            <input type="text" id="created_at" class="form-control" disabled
                                                   value="{{ App\AdditionalClasses\Date::timestampToShamsiWithDay_andNameOfMonth_andTime($This->created_at) }}">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic multiple Column Form section end -->

@endsection
@section('script')
    <script>

    </script>
@endsection
