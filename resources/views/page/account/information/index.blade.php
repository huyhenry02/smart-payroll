@extends('page.account.layouts.main')
@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    /**
     * Helper hiển thị an toàn
     */
    $val = function($v, $fallback = '—') {
        if ($v === null) return $fallback;
        if (is_string($v) && trim($v) === '') return $fallback;
        return $v;
    };

    $fmtDate = function($date, $format = 'd/m/Y') use ($val) {
        if (!$date) return '—';
        try {
            return Carbon::parse($date)->format($format);
        } catch (\Throwable $e) {
            return $val($date);
        }
    };

    $genderText = function($g) {
        return match($g) {
            'male' => 'Nam',
            'female' => 'Nữ',
            'other' => 'Khác',
            default => '—'
        };
    };

    $statusBadge = function($s) {
        $s = (string)$s;
        return match($s) {
            'working' => ['Đang làm việc', 'success'],
            'resigned' => ['Đã nghỉ', 'secondary'],
            default => ['—', 'light']
        };
    };

    $money = function($v) {
        if ($v === null || $v === '') return '—';
        return number_format((float)$v, 0, ',', '.') . ' đ';
    };

    $deptName = data_get($employee, 'department.name') ?? data_get($employee, 'department_name');
    $posName  = data_get($employee, 'position.name') ?? data_get($employee, 'position_name');

    [$statusText, $statusType] = $statusBadge(data_get($employee, 'employment_status'));
@endphp
@section('contentAccount')
    <div class="page-inner">
        <div class="row">
            <div class="col-12">

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Thông tin cá nhân</h4>
                        <div class="text-muted" style="font-size: 13px;">
                            Xem thông tin hồ sơ của bạn (cập nhật khi cần).
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="position-relative d-inline-block">
                                    @if(!empty($employee->avatar))
                                        <img src="{{ $employee->avatar }}"
                                             alt="Avatar"
                                             class="rounded-3 shadow-sm"
                                             style="width: 130px; height: 130px; object-fit: cover;">
                                    @else
                                        <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center shadow-sm"
                                             style="width: 130px; height: 130px;">
                                            <i class="fas fa-user-circle text-muted" style="font-size: 48px;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <h5 class="mb-0">{{ $val($employee->full_name) }}</h5>
                                    <span class="badge bg-{{ $statusType }}">{{ $statusText }}</span>
                                    @if(!empty($employee->employee_code))
                                        <span class="badge bg-light text-dark border">
                                        Mã NV: {{ $employee->employee_code }}
                                    </span>
                                    @endif
                                </div>

                                <div class="text-muted mt-2">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <i class="far fa-envelope me-1"></i>
                                            <span>{{ $val(auth()->user()->email ?? '') }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-phone me-1"></i>
                                            <span>{{ $val($employee->phone) }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-briefcase me-1"></i>
                                            <span>{{ $val($posName) }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-building me-1"></i>
                                            <span>{{ $val($deptName) }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="far fa-calendar-check me-1"></i>
                                            <span>Ngày vào làm: {{ $fmtDate($employee->start_date) }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <span>{{ $val($employee->address) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="small text-muted">Giới tính</div>
                                        <div class="fw-semibold">{{ $genderText($employee->gender) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">Ngày sinh</div>
                                        <div class="fw-semibold">{{ $fmtDate($employee->dob) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">Thâm niên</div>
                                        <div class="fw-semibold">{{ $val($employee->seniority) }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small text-muted">Lương cơ bản</div>
                                        <div class="fw-semibold">{{ $money($employee->salary_gross) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-stretch">
                    <div class="col-lg-6 d-flex">
                        <div class="card mb-3 w-100 h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-id-card me-2"></i>Thông tin cá nhân
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Số CMND/CCCD</div>
                                        <div class="fw-semibold">{{ $val($employee->identity_number) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Ngày cấp</div>
                                        <div class="fw-semibold">{{ $fmtDate($employee->identity_issued_date) }}</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="small text-muted">Nơi cấp</div>
                                        <div class="fw-semibold">{{ $val($employee->identity_issued_place) }}</div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="small text-muted">Địa chỉ</div>
                                        <div class="fw-semibold">{{ $val($employee->address) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex">
                        <div class="card mb-3 w-100 h-100">
                            <div class="card-header">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-briefcase me-2"></i>Thông tin công việc
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Phòng ban</div>
                                        <div class="fw-semibold">{{ $val($deptName) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Chức vụ</div>
                                        <div class="fw-semibold">{{ $val($posName) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Ngày vào làm</div>
                                        <div class="fw-semibold">{{ $fmtDate($employee->start_date) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Tình trạng làm việc</div>
                                        <div>
                                            <span class="badge bg-{{ $statusType }}">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="small text-muted">Loại hợp đồng</div>
                                        <div class="fw-semibold">{{ $val($employee->contract_type) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Lương cơ bản</div>
                                        <div class="fw-semibold">{{ $money($employee->salary_gross) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Mã số thuế</div>
                                        <div class="fw-semibold">{{ $val($employee->tax_code) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-stretch mt-2">
                    <div class="col-lg-6 d-flex">
                        <div class="card mb-3 w-100 h-100">
                            <div class="card-header">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-graduation-cap me-2"></i>Học vấn
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Trình độ học vấn</div>
                                        <div class="fw-semibold">{{ $val($employee->education_level) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Chuyên ngành</div>
                                        <div class="fw-semibold">{{ $val($employee->specialization) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex">
                        <div class="card mb-3 w-100 h-100">
                            <div class="card-header">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="fas fa-university me-2"></i>Thông tin ngân hàng
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted">Số tài khoản</div>
                                        <div class="fw-semibold">{{ $val($employee->bank_account) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted">Ngân hàng</div>
                                        <div class="fw-semibold">{{ $val($employee->bank_name) }}</div>
                                    </div>
                                </div>
                                <div class="alert alert-light border mt-3 mb-0" style="font-size: 13px;">
                                    <i class="fas fa-lock me-1 text-muted"></i>
                                    Thông tin ngân hàng chỉ phục vụ nghiệp vụ thanh toán nội bộ.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-2">
                    <button class="btn btn-outline-secondary" type="button" onclick="window.history.back()">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection
