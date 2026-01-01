<?php
$routesAccount = [
    'account.showInformation',
    'account.showPersonalAccounting',
    'account.showPersonalAttendance',
];
$isActiveAccount = collect($routesAccount)->contains(fn($route) => request()->routeIs($route));
$month = date('Y-m');
?>
<div class="sidebar">
    <div class="sidebar-logo">
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ $isActiveAccount ? 'active' : '' }}">
                    <a
                        data-bs-toggle="collapse"
                        href="#system"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-user-cog"></i>
                        <p>Trang cá nhân</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="system">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs([
                                        'account.showInformation',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('account.showInformation') }}">
                                    <span class="sub-item">Thông tin cá nhân</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'account.showPersonalAttendance',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('account.showPersonalAttendance', ['month' => $month]) }}">
                                    <span class="sub-item">Bảng công</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs([
                                        'account.showPersonalAccounting',
                                        ]) ? 'active' : '' }}"
                            >
                                <a href="{{ route('account.showPersonalAccounting') }}">
                                    <span class="sub-item">Bảng lương</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
