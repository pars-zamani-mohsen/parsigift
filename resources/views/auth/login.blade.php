@extends('auth.app')
@section('content')
    <div class="col-12 col-lg-4">
        <a href="{{ url('/') }}"><img class="img-fluid" src="{{ asset('/images/logo/parsicanada-farsi.png') }}" alt="Logo"></a>
    </div>
    <h1 class="auth-title">ورود</h1>
    <p class="auth-subtitle mb-5">اطلاعاتی که در زمان ثبت نام استفاده کرده اید را در فرم زیر وارد کنید.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
            <input type="text" name="tell" class="form-control form-control-xl @error('tell') is-invalid @enderror" placeholder="شماره تلفن همراه" value="{{ old('tell') }}" required autofocus>
            @error('tell')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="کلمه عبور" name="password" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-check form-check-lg d-flex align-items-end">
            <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label text-gray-600" for="remember">
                مرا بخاطر بسپار
            </label>
        </div>
        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">ورود</button>
    </form>
    {{--<div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray-600">هنوز ثبت نام نکرده اید؟
            <a href="{{ url('/register') }}" class="font-bold">ثبت نام</a></p>
        <p><a class="font-bold" href="{{ url('/password/reset') }}">کلمه عبور خود را فراموش کرده اید؟</a></p>
    </div>--}}
@endsection
