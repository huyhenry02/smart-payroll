@php use App\Models\User; @endphp
<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header">
            <a href="#" class="logo">

            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <!-- Navbar Header -->
    <nav
        class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
        style="background-color: #2379b3"
    >
        <div class="container-fluid">
            @php
                use Illuminate\Support\Facades\Route;
                $routeName = Route::currentRouteName();
            @endphp
            <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
                style="color: #fff"
            >
                <h3 style="color: #fff">
                    @switch( $routeName )
                        @case('account.showInformation')
                            Thông tin cá nhân
                            @break
                        @case('account.showPersonalAccounting')
                            Bảng lương cá nhân
                            @break
                        @case('account.showSummaryAccounting')
                            Bảng công cá nhân
                            @break
                    @endswitch
                </h3>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home" style="color: #fff"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item" style="color: #fff">
                        @if ( Str::startsWith($routeName, 'account'))
                            <a href="{{ route('general_catalog.showIndexEmployee') }}" style="color: #fff">Trang cá nhân</a>
                        @endif
                    </li>
                </ul>
            </nav>
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                    class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                    <a
                        class="nav-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        href="#"
                        role="button"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                    </ul>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a
                        class="dropdown-toggle profile-pic"
                        data-bs-toggle="dropdown"
                        href="#"
                        aria-expanded="false"
                    >
                        <div class="avatar-sm">
                            <img
                                src="/assets/img/profile.jpg"
                                alt="..."
                                class="avatar-img rounded-circle"
                            />
                        </div>
                        <span class="profile-username">
                      <span class="op-7" style="color: #fff">Xin chào,</span>
                      <span class="fw-bold" style="color: #fff">{{ auth()->user()?->employee->full_name ?? '' }}</span>
                    </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img
                                            src="/assets/img/profile.jpg"
                                            alt="image profile"
                                            class="avatar-img rounded"
                                        />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ auth()->user()?->employee->full_name ?? '' }}</h4>
                                        <p class="text-muted">{{ auth()->user()?->roleInfo->name ?? '' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('general_catalog.showIndexEmployee') }}">Quản trị hệ thống</a>
                                <a class="dropdown-item" href="{{ route('account.showInformation') }}">Trang cá nhân</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('auth.logout') }}">Đăng xuất</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
