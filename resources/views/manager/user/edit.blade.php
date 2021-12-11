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
                            @php
                                if(isset($This) && $This)
                                    $url = url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/'. $modulename['en'] .'/' . $This['id']);
                                else
                                    $url = url('/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/'. $modulename['en']);
                            @endphp
                            <form class="form" name="ticket" method="post" action="{{ $url }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @if(isset($This) && $This)
                                        <input type="hidden" name="id" value="{{ $This['id'] }}">
                                        @method('put')
                                    @endif

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name">نام <i class="text-danger"> * </i></label>
                                            <input type="text" id="name" class="form-control" name="name" required autofocus autocomplete="name"
                                                   value="{{ ((isset($This) && $This->name)) ? $This->name : old('name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tell">تلفن همراه <i class="text-danger"> * </i></label>
                                            <input type="text" id="tell" class="form-control" name="tell" required
                                                   value="{{ ((isset($This) && $This->tell)) ? $This->tell : old('tell') }}">
                                        </div>
                                    </div>

                                    @if(isset($This) && $This)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="password">کلمه عبور </label>
                                                <input type="checkbox" class="form-check-input form-check-danger" id="password_ch" onclick="password_checker()">
                                                <input type="password" id="password" class="form-control" name="password" disabled
                                                       value="{{ old('password') }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="password">کلمه عبور <i class="text-danger"> * </i></label>
                                                <input type="password" id="password" class="form-control" name="password" required
                                                       value="{{ ((isset($This) && $This->password)) ? $This->password : old('password') }}">
                                            </div>
                                        </div>
                                    @endif

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="role">نقش <i class="new-user-req text-danger"> * </i> </label>
                                                <select name="role" id="role" class="form-select" required>
                                                    <option value="user" @if(isset($This) && $This['role'] == 'user' || old('role') == 'user') selected @endif>User</option>
                                                    <option value="admin" @if(isset($This) && $This['role'] == 'admin' || old('role') == 'admin') selected @endif>Admin</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="cart_number">شماره کارت<i class="text-danger"> * </i></label>
                                                <input type="text" id="cart_number" class="form-control" name="cart_number" required maxlength="16" pattern="\d*"
                                                       value="{{ ((isset($This) && $This->cart_number)) ? $This->cart_number : old('cart_number') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="nesbat">نسبت</label>
                                                <input type="text" id="nesbat" class="form-control" name="nesbat"
                                                       value="{{ ((isset($This) && $This->nesbat)) ? $This->nesbat : old('nesbat') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="invisible">-</label>
                                            <div class="form-check">
                                                <div class="custom-control custom-checkbox">
                                                    <label class="form-check-label" for="r_and_d_check">تایید R&D</label>
                                                    <input type="checkbox" class="form-check-input form-check-info" name="r_and_d_check" id="r_and_d_check"
                                                           @if(isset($This) && $This->r_and_d_check) checked @endif>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <label class="invisible">-</label>
                                            <div class="form-check">
                                                <div class="custom-control custom-checkbox">
                                                    <label class="form-check-label" for="active">فعال</label>
                                                    <input type="checkbox" class="form-check-input form-check-info" name="active" id="active"
                                                           @if(isset($This) && !$This->active) @else checked @endif>
                                                </div>
                                            </div>
                                        </div>


                                    <div class="row col-12 d-flex justify-content-end mt-5">
                                        <div class="col-xxl-2 col-md-2 col-12">
                                            <div class="form-group">
                                                <label for="submit" class="invisible">ثبت</label>
                                                <button type="submit" id="submit" class="btn btn-primary me-1 mb-1 form-control">
                                                    <i class="bi bi-check bi-line-height"></i> ثبت </button>
                                            </div>
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
        function password_checker()
        {
            if (document.getElementById('password_ch').checked)
            {
                document.getElementById("password").disabled = false;
            } else {
                document.getElementById("password").disabled = true;
            }
        }
    </script>
@endsection
