@php $login_user = \Illuminate\Support\Facades\Auth::user() @endphp
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ url('/' . App\Http\Controllers\HomeController::fetch_manager_pre_url()) }}"><img src="{{ asset('/images/logo/parsicanada-farsi.png') }}" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">فهرست</li>

                @php $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/dashboard'; @endphp
                <li class="sidebar-item" data-url="{{ $url }}">
                    <a href="{{ url($url) }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>داشبورد</span>
                    </a>
                </li>

                @if(Auth::user()->role == 'admin')
                    @php $moduleModel = '\App\Gift'; $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $moduleModel::$modulename['en']; @endphp
                    <li class="sidebar-item" data-url="{{ $url }}">
                        <a href="{{ url($url) }}" class='sidebar-link'>
                            <i class="bi bi-gift"></i>
                            <span>{{ $moduleModel::$modulename['fa'] }}</span>
                        </a>
                    </li>
                    @php $moduleModel = '\App\GiftRequest'; $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $moduleModel::$modulename['en']; @endphp
                    <li class="sidebar-item" data-url="{{ $url }}">
                        <a href="{{ url($url) }}" class='sidebar-link'>
                            <i class="bi bi-signpost-2"></i>
                            <span>{{ $moduleModel::$modulename['fa'] }}</span>
                        </a>
                    </li>

                    <li class="sidebar-item  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-gear"></i>
                            <span>تنظیمات</span>
                        </a>
                        <ul class="submenu">

                            @php  $moduleModel = '\App\User';  $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $moduleModel::$modulename['en']; @endphp
                            <li class="submenu-item" data-url="{{ $url }}">
                                <a href="{{ url($url) }}">
                                    <i class="bi bi-people"></i><span>{{ $moduleModel::$modulename['fa'] }}</span>
                                </a>
                            </li>

                            @php  $moduleModel = '\App\Recyclebin';  $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $moduleModel::$modulename['en']; @endphp
                            <li class="submenu-item" data-url="{{ $url }}">
                                <a href="{{ url($url) }}">
                                    <i class="bi bi-trash"></i><span>{{ $moduleModel::$modulename['fa'] }}</span>
                                </a>
                            </li>

                            @php  $moduleModel = '\App\ActivityLog';  $url = '/'. App\Http\Controllers\HomeController::fetch_manager_pre_url() .'/' . $moduleModel::$modulename['en']; @endphp
                            <li class="submenu-item" data-url="{{ $url }}">
                                <a href="{{ url($url) }}">
                                    <i class="bi bi-clipboard"></i><span>{{ $moduleModel::$modulename['fa'] }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="sidebar-item">
                    <a id="_logout" class='sidebar-link' href="#">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>خروج</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
