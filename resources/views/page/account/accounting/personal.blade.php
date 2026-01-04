@extends('page.account.layouts.main')
@php
    use Carbon\Carbon;
    $money = function ($v) {
        if ($v === null || $v === '') return 'N/A';
        return number_format((float)$v, 0, ',', '.') . 'đ';
    };
    $fmtDate = function ($date, $format = 'd/m/Y') {
        if (!$date) return 'N/A';
        try {
            return Carbon::parse($date)->format($format);
        } catch (Throwable $e) {
            return $date;
        }
    };
@endphp
@section('contentAccount')
    <div class="page-inner">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Năm:</label>
                        <select class="form-control" id="year" name="year">
                            <option value="">-- Chọn năm --</option>
                            @php
                                $years = collect($payrolls)->pluck('year')->filter()->unique()->sort()->values();
                            @endphp
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tháng:</label>
                        <select class="form-control" id="month" name="month">
                            <option value="">-- Chọn tháng --</option>
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Bảng lương</h6>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover" id="payroll-table">
                                <thead>
                                <tr>
                                    <th width="2%">STT</th>
                                    <th>Tiêu đề bảng lương</th>
                                    <th width="8%">Tháng</th>
                                    <th width="8%">Năm</th>
                                    <th width="18%">Người tạo</th>
                                    <th width="14%" class="text-end">Thành tiền</th>
                                    <th width="8%" class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody id="payroll-tbody">
                                @foreach($payrolls as $key => $p)
                                    @php
                                        $departmentName = $p->department_name ?? 'N/A';
                                        $departmentName = $departmentName === 'N/A' ? (data_get($p, 'department.name') ?? 'N/A') : $departmentName;
                                        $title = $p->title ?? "Bảng lương tháng {$p->month} năm {$p->year}";
                                        $salaryGross = $p->salary_gross ?? $p->salary_v1 ?? 0;
                                    @endphp

                                    <tr
                                        class="payroll-row"
                                        data-year="{{ (int)$p->year }}"
                                        data-month="{{ (int)$p->month }}"
                                        data-title="{{ $title }}"
                                        data-employee-code="{{ $p->employee->employee_code ?? ''}}"

                                        data-creator="{{ (data_get($p, 'createdBy.full_name')) ?? '' }}"
                                        data-full-name="{{ (data_get($p, 'employee.full_name')) ?? '' }}"
                                        data-department-name="{{ (data_get($p, 'employee.department.name')) ?? '' }}"
                                        data-amount="{{ $money($p->net_salary_after_tax ?? 0) }}"

                                        data-salary-gross="{{ $money($salaryGross) }}"
                                        data-total-allowance="{{ $money($p->total_allowance ?? 0) }}"
                                        data-total-deduction="{{ $money($p->total_deduction ?? 0) }}"
                                        data-total-bonus="{{ $money($p->total_bonus ?? 0) }}"
                                        data-working-shift-amount ="{{ $money($p->working_shift_amount ?? 0) }}"
                                        data-tax-amount="{{ $money($p->tax_amount ?? 0) }}"
                                        data-tax-rate="{{ $p->tax_rate ?? 'N/A' }}"
                                        data-net-before="{{ $money($p->net_salary_before_tax ?? 0)}}"
                                        data-net-after="{{ $money($p->net_salary_after_tax ?? 0) }}"
                                        data-created-at="{{ $fmtDate($p->created_at) }}"
                                    >
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $title }}</td>
                                        <td class="text-center">{{ $p->month }}</td>
                                        <td class="text-center">{{ $p->year }}</td>
                                        <td>{{ (data_get($p, 'createdBy.full_name'))?? '' }}</td>
                                        <td class="text-end">{{ $money($p->net_salary_after_tax) }}</td>
                                        <td class="text-center">
                                            <button
                                                class="btn btn-sm btn-info btn-view-payroll"
                                                data-bs-toggle="modal"
                                                data-bs-target="#payrollDetailModal"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3">
                            <div class="d-flex align-items-center gap-2">
                                <select id="pageSize" class="form-control" style="width: 130px;">
                                    <option value="20">20 / trang</option>
                                    <option value="50">50 / trang</option>
                                    <option value="100">100 / trang</option>
                                    <option value="999999">Tất cả</option>
                                </select>
                                <div class="ms-2">
                                    Tổng số bản ghi: <span id="totalCount">{{ count($payrolls) }}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary" id="prevPage" type="button">Trước</button>
                                <div>Trang <span id="currentPage">1</span> / <span id="totalPage">1</span></div>
                                <button class="btn btn-sm btn-outline-secondary" id="nextPage" type="button">Sau</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payrollDetailModal" tabindex="-1" aria-labelledby="payrollDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payrollDetailModalLabel">Chi tiết bảng lương</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="small text-muted">Họ tên nhân viên</div>
                            <div class="fw-semibold" id="modal-full-name">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Mã nhân viên</div>
                            <div class="fw-semibold" id="modal-employee-code">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Phòng ban</div>
                            <div class="fw-semibold" id="modal-department-name">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Người tạo</div>
                            <div class="fw-semibold" id="modal-creator">N/A</div>
                        </div>

                        <div class="col-md-12">
                            <div class="small text-muted">Tiêu đề bảng lương</div>
                            <div class="fw-semibold" id="modal-title">N/A</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-muted">Tháng</div>
                            <div class="fw-semibold" id="modal-month">N/A</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Năm</div>
                            <div class="fw-semibold" id="modal-year">N/A</div>
                        </div>

                        <hr class="my-2">

                        <div class="col-md-6">
                            <div class="small text-muted">Lương cơ bản</div>
                            <div class="fw-semibold" id="modal-salary-gross">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Tổng phụ cấp</div>
                            <div class="fw-semibold" id="modal-total-allowance">N/A</div>
                        </div>

                        <div class="col-md-6">
                            <div class="small text-muted">Tổng khoản trích</div>
                            <div class="fw-semibold" id="modal-total-deduction">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Tổng khoản thưởng</div>
                            <div class="fw-semibold" id="modal-total-bonus">N/A</div>
                        </div>

                        <div class="col-md-6">
                            <div class="small text-muted">Tổng tiền làm thêm</div>
                            <div class="fw-semibold" id="modal-working-shift-amount">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Thuế TNCN</div>
                            <div class="fw-semibold" id="modal-tax-amount">N/A</div>
                        </div>

                        <div class="col-md-6">
                            <div class="small text-muted">Thuế suất (%)</div>
                            <div class="fw-semibold" id="modal-tax-rate">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Thành tiền</div>
                            <div class="fw-bold text-primary" id="modal-amount">N/A</div>
                        </div>

                        <div class="col-md-6">
                            <div class="small text-muted">Lương thực lĩnh trước thuế</div>
                            <div class="fw-semibold" id="modal-net-before">N/A</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Lương thực lĩnh sau thuế</div>
                            <div class="fw-semibold" id="modal-net-after">N/A</div>
                        </div>

                        <div class="col-md-12">
                            <div class="small text-muted">Ngày tạo</div>
                            <div class="fw-semibold" id="modal-created-at">N/A</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.btn-view-payroll', function () {
            const row = $(this).closest('tr');

            $('#modal-status').text(row.data('status'));
            $('#modal-creator').text(row.data('creator'));
            $('#modal-employee-code').text(row.data('employee-code'));
            $('#modal-full-name').text(row.data('full-name'));
            $('#modal-department-name').text(row.data('department-name'));
            $('#modal-amount').text(row.data('amount'));

            $('#modal-title').text(row.data('title'));

            $('#modal-month').text(row.data('month'));
            $('#modal-year').text(row.data('year'));

            $('#modal-salary-gross').text(row.data('salary-gross'));
            $('#modal-total-allowance').text(row.data('total-allowance'));
            $('#modal-total-deduction').text(row.data('total-deduction'));
            $('#modal-total-bonus').text(row.data('total-bonus'));
            $('#modal-working-shift-amount').text(row.data('working-shift-amount'));
            $('#modal-tax-amount').text(row.data('tax-amount'));
            $('#modal-tax-rate').text(row.data('tax-rate'));

            $('#modal-net-before').text(row.data('net-before'));
            $('#modal-net-after').text(row.data('net-after'));
            $('#modal-created-at').text(row.data('created-at'));
        });

        $(document).ready(function () {
            const $rows = $('#payroll-tbody tr');
            let pageSize = parseInt($('#pageSize').val(), 10);
            let currentPage = 1;

            function applyFilter() {
                const year  = $('#year').val();
                const month = $('#month').val();
                const title = ($('#title').val() || '').toLowerCase().trim();

                let $filtered = $rows;

                $rows.hide();

                $filtered = $filtered.filter(function () {
                    const $r = $(this);
                    const rYear = ($r.data('year') || '').toString();
                    const rMonth = ($r.data('month') || '').toString();
                    const rTitle = ($r.find('td:nth-child(3)').text() || '').toLowerCase();

                    if (year && rYear !== year) return false;
                    if (month && rMonth !== month) return false;
                    if (title && !rTitle.includes(title)) return false;
                    return true;
                });

                const total = $filtered.length;
                $('#totalCount').text(total);

                const totalPage = Math.max(1, Math.ceil(total / pageSize));
                if (currentPage > totalPage) currentPage = totalPage;

                $('#totalPage').text(totalPage);
                $('#currentPage').text(currentPage);

                const start = (currentPage - 1) * pageSize;
                const end = pageSize >= 999999 ? total : start + pageSize;

                $filtered.slice(start, end).show();

                $('#prevPage').prop('disabled', currentPage <= 1);
                $('#nextPage').prop('disabled', currentPage >= totalPage);
            }

            $('#year, #month, #title').on('input change', function () {
                currentPage = 1;
                applyFilter();
            });

            $('#pageSize').on('change', function () {
                pageSize = parseInt($(this).val(), 10);
                currentPage = 1;
                applyFilter();
            });

            $('#prevPage').on('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilter();
                }
            });

            $('#nextPage').on('click', function () {
                currentPage++;
                applyFilter();
            });

            // init
            applyFilter();
        });
    </script>
@endsection
