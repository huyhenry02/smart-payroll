@php use App\Models\User; @endphp
<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header">
            <a href="#" class="logo">
                <img
                    src="/assets/img/logo.svg"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20"
                />
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
        style="background-color: #a01f23"
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
                        @case('general_catalog.showIndexDepartment')
                            Danh sách phòng ban
                            @break
                        @case('general_catalog.showIndexPosition')
                            Danh sách chức vụ
                            @break
                        @case('general_catalog.showIndexEmployee')
                            Danh sách nhân viên
                            @break
                        @case('general_catalog.showUpdateEmployee')
                            Cập nhật nhân viên
                            @break
                        @case('general_catalog.showIndexWorkingShift')
                            Danh sách ca làm việc
                            @break
                        @case('general_catalog.showIndexDeduction')
                            Danh sách khấu trừ
                            @break
                        @case('general_catalog.showIndexAllowance')
                            Danh sách phụ cấp
                            @break
                        @case('system.showIndexUser')
                            Danh sách người dùng
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
                        @if ( Str::startsWith($routeName, 'general_catalog'))
                            <a href="{{ route('general_catalog.showIndexEmployee') }}" style="color: #fff">Danh mục chung</a>
                        @elseif ( Str::startsWith($routeName, 'system'))
                            <a href="{{ route('system.showIndexUser') }}" style="color: #fff">Quản trị hệ thống</a>
                        @endif
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        @switch( $routeName )
                            @case('general_catalog.showIndexDepartment')
                                <a href="{{ route('general_catalog.showIndexDepartment') }}" style="color: #fff">Phòng ban</a>
                                @break
                            @case('general_catalog.showIndexPosition')
                                <a href="{{ route('general_catalog.showIndexPosition') }}" style="color: #fff">Chức vụ</a>
                                @break
                            @case('general_catalog.showIndexEmployee')
                            @case('general_catalog.showUpdateEmployee')
                                <a href="{{ route('general_catalog.showIndexEmployee') }}" style="color: #fff">Nhân viên</a>
                                @break
                            @case('general_catalog.showIndexWorkingShift')
                                <a href="{{ route('general_catalog.showIndexWorkingShift') }}" style="color: #fff">Ca làm thêm</a>
                                @break
                            @case('general_catalog.showIndexDeduction')
                                <a href="{{ route('general_catalog.showIndexDeduction') }}" style="color: #fff">Trích nộp theo lương</a>
                                @break
                            @case('general_catalog.showIndexAllowance')
                                <a href="{{ route('general_catalog.showIndexAllowance') }}" style="color: #fff">Phụ cấp và trợ cấp</a>
                                @break
                            @case('system.showIndexUser')
                                <a href="{{ route('system.showIndexUser') }}" style="color: #fff">Người dùng</a>
                                @break
                        @endswitch
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
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input
                                    type="text"
                                    placeholder="Search ..."
                                    class="form-control"
                                />
                            </div>
                        </form>
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
                                        <p class="text-muted">{{ auth()->user()?->role ? User::ROLES[auth()->user()?->role] : '' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('auth.logout') }}">Đăng xuất</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
