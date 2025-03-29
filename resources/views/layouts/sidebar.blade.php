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
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#system"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-home"></i>
                        <p>Quản trị hệ thống</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="system">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('system.showIndexUser') }}">
                                    <span class="sub-item">Danh sách người dùng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#general_catalog"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-home"></i>
                        <p>Danh mục chung</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="general_catalog">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('general_catalog.showIndexEmployee') }}">
                                    <span class="sub-item">Nhân viên</span>
                                </a>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav1">
                                    <span class="sub-item">Phòng ban, Chức vụ</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse show" id="subnav1">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="{{ route('general_catalog.showIndexDepartment') }}">
                                                <span class="sub-item">Phòng ban</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('general_catalog.showIndexPosition') }}">
                                                <span class="sub-item">Chức vụ</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('general_catalog.showIndexWorkingShift') }}">
                                    <span class="sub-item">Ca làm thêm</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('general_catalog.showIndexAllowance') }}">
                                    <span class="sub-item">Phụ cấp và trợ cấp</span>
                                </a>
                            </li>
                            <li>
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
                        <i class="fas fa-home"></i>
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
                        <i class="fas fa-home"></i>
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
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href="#deduction"
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-home"></i>
                        <p>Quản lý trích nộp</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="deduction">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="#">
                                    <span class="sub-item">Bảng tổng hợp trích lương</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Trích lương cho nhân viên</span>
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
                        <i class="fas fa-home"></i>
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
