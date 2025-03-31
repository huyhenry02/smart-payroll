<?php
$routesSystem = [
    'system.showIndexUser',
];
$isActiveSystem = collect($routesSystem)->contains(fn($route) => request()->routeIs($route));

$routesGeneralCatalog = [
    'general_catalog.showIndexDepartment',
    'general_catalog.showIndexPosition',
    'general_catalog.showIndexEmployee',
    'general_catalog.showIndexWorkingShift',
    'general_catalog.showIndexDeduction',
    'general_catalog.showIndexAllowance',
];
$isActiveGeneralCatalog = collect($routesGeneralCatalog)->contains(fn($route) => request()->routeIs($route));

$routesDepartmentPosition = [
    'general_catalog.showIndexDepartment',
    'general_catalog.showIndexPosition',
];
$isActiveDepartmentPosition = collect($routesDepartmentPosition)->contains(fn($route) => request()->routeIs($route));

$routesAllowanceDeduction = [
    'allowance_deduction.showIndexDeduction',
    'allowance_deduction.showIndexAllowance',
];
$isActiveAllowanceDeduction = collect($routesAllowanceDeduction)->contains(fn($route) => request()->routeIs($route));
?>
<div class="sidebar">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header">
            <a href="#" class="logo">
                <img
                    src="/assets/img/logo.svg"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="22"
                    style=" margin-left: 10px;"
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ $isActiveSystem ? 'active' : '' }}">
                    <a
                        data-bs-toggle="collapse"
                        href="#system"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-user-cog"></i>
                        <p>Quản trị hệ thống</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isActiveSystem ? 'show' : '' }}" id="system">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs([
                                        'system.showIndexUser',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('system.showIndexUser') }}">
                                    <span class="sub-item">Danh sách người dùng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ $isActiveGeneralCatalog ? 'active' : '' }}">
                    <a
                        data-bs-toggle="collapse"
                        href="#general_catalog"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-list-alt"></i>
                        <p>Danh mục chung</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isActiveGeneralCatalog ? 'show' : '' }}" id="general_catalog">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexEmployee',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('general_catalog.showIndexEmployee') }}">
                                    <span class="sub-item">Nhân viên</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexDepartment',
                                        'general_catalog.showIndexPosition',
                                        ]) ? 'active' : '' }}"
                            >
                                <a data-bs-toggle="collapse" href="#subnav1">
                                    <span class="sub-item">Phòng ban, Chức vụ</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse {{ $isActiveDepartmentPosition ? 'show' : '' }}" id="subnav1">
                                    <ul class="nav nav-collapse subnav">
                                        <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexDepartment',
                                        ]) ? 'active' : '' }}"
                                        >
                                            <a href="{{ route('general_catalog.showIndexDepartment') }}">
                                                <span class="sub-item">Phòng ban</span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexPosition',
                                        ]) ? 'active' : '' }}"
                                        >
                                            <a href="{{ route('general_catalog.showIndexPosition') }}">
                                                <span class="sub-item">Chức vụ</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexWorkingShift',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('general_catalog.showIndexWorkingShift') }}">
                                    <span class="sub-item">Ca làm thêm</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexAllowance',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('general_catalog.showIndexAllowance') }}">
                                    <span class="sub-item">Phụ cấp và trợ cấp</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'general_catalog.showIndexDeduction',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('general_catalog.showIndexDeduction') }}">
                                    <span class="sub-item">Trích nộp theo lương</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Tài khoản kế toán</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Đơn vị tiền tệ và tỷ giá</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#attendance"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-clock"></i>
                        <p>Quản lý chấm công</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="attendance">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Bảng công</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#accounting"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-calculator"></i>
                        <p>Tính lương và thanh toán</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="accounting">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Bảng lương</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ $isActiveAllowanceDeduction ? 'active' : '' }}">
                    <a
                        data-bs-toggle="collapse"
                        href="#deduction_allowance"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-wallet"></i>
                        <p>Trích nộp và phụ cấp</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isActiveAllowanceDeduction ? 'show' : '' }}" id="deduction_allowance">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs([
                                        'allowance_deduction.showIndexDeduction',
                                        ]) ? 'active' : '' }}">
                                <a href="{{ route('allowance_deduction.showIndexDeduction') }}">
                                    <span class="sub-item">Bảng tổng hợp trích nộp</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'allowance_deduction.showIndexAllowance',
                                        ]) ? 'active' : '' }}">
                                <a href="{{ route('allowance_deduction.showIndexAllowance') }}">
                                    <span class="sub-item">Bảng tổng hợp phụ cấp</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#report"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-chart-line"></i>
                        <p>Hạch toán và báo cáo</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="report">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Quản lý chứng từ</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Quản lý sổ sách</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
