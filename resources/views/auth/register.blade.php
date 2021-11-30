@extends('auth.app')
@section('content')
    <div class="col-12 col-lg-4">
        <a href="{{ url('/') }}"><img class="img-fluid" src="{{ asset('/images/logo/parsicanada-farsi.png') }}" alt="Logo"></a>
    </div>
    <h1 class="auth-title">ثبت نام</h1>
    <p class="auth-subtitle mb-5">لطفا اطلاعات مورد نیاز زیر را تکمیل نمایید.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('name') is-invalid @enderror" name="name"
                   placeholder="نام مستعار" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('mobile') is-invalid @enderror" name="mobile"
                   placeholder="شماره تلفن همراه" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus>
            @error('mobile')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-phone"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" name="password"
                   required autocomplete="password" placeholder="کلمه عبور">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl" name="password_confirmation"
                   placeholder="تکرار کلمه عبور" required autocomplete="password">
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">ثبت نام</button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>در گذشته ثبت نام کرده اید؟
            <a href="{{ url('/login') }}" class="font-bold">ورود</a></p>
    </div>
@endsection
