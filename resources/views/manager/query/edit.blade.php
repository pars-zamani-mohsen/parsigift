@extends('manager.base-forms.edit')

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
                                            <label for="title">عنوان <i class="text-danger"> * </i></label>
                                            <input type="text" id="title" class="form-control" name="title" required autofocus autocomplete="title"
                                                   value="{{ ((isset($This) && $This->title)) ? $This->title : old('title') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="url">آدرس<i class="text-danger"> * </i></label>
                                            <input type="text" id="url" class="form-control" name="url" placeholder="https://parsicanada.com" dir="ltr" required
                                                   value="{{ ((isset($This) && $This->url)) ? $This->url : old('url') }}">
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
