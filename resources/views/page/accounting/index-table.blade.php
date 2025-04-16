@php use App\Models\Allowance;use App\Models\Employee;use App\Models\Payroll;use Illuminate\Database\Eloquent\Collection; @endphp
@php use Carbon\Carbon; @endphp
<table class="display table table-bordered table-hover accounting-table">
    <thead>
    <tr>
        <th class="text-center sticky-col sticky-col-1" rowspan="2">STT</th>
        <th class="text-center sticky-col sticky-col-2" rowspan="2">Họ và tên</th>
        <th class="text-center sticky-col sticky-col-3" rowspan="2">Ngân hàng</th>
        <th class="text-center sticky-col sticky-col-4" rowspan="2">Số tài khoản</th>
        <th class="text-center sticky-col sticky-col-5" rowspan="2">Đơn giá lương V1</th>
        <th colspan="5" class="text-center">Hệ số lương cơ bản</th>
        <th rowspan="2" class="text-center">Lương V1</th>
        <th colspan="3" class="text-center">Các khoản phụ cấp thành tiền</th>
        <th rowspan="2" class="text-center">Tổng phụ cấp</th>
        <th colspan="{{ $deductions->count() }}" class="text-center">Các khoản trích nộp thành tiền</th>
        <th rowspan="2" class="text-center">Tổng trích nộp</th>
        <th rowspan="2" class="text-center">Làm thêm giờ</th>
        <th rowspan="2" class="text-center">Tiền thưởng</th>
        <th rowspan="2" class="text-center">Thực lĩnh (Trước thuể)</th>
    </tr>
    <tr>
        <th class="text-center">V1</th>
        @foreach(Allowance::TYPES_REALITY_CODE as $key => $val)
            <th class="text-center">{{ $val }}</th>
        @endforeach
        <th class="text-center">N.Lương</th>
        @foreach(Allowance::TYPES_REALITY_TEXT as $key => $val)
            <th class="text-center">{{ $val }}</th>
        @endforeach
        @foreach($deductions as $key => $val)
            <th class="text-center">{{ $val->name }}</th>
        @endforeach
    </tr>
    </thead>
    @php
        /** @var Collection|Employee[] $employees */
    @endphp
    @php
        $hasPayroll = $employees->filter(fn($e) => $e->payrolls->isNotEmpty())->isNotEmpty();
    @endphp
    <tbody>
    @if ( $hasPayroll )
        @foreach($employees as $index => $employee)
            @php
                $payroll = $employee->payrolls->first();
                $attendance = $employee->attendance->first();
                $workingDays = $attendance->working_days ?? 0;
                $v1 = $employee->salary_factor;
                $v1Salary = $payroll->salary_v1 ?? 0;
                $bonus = $payroll->total_bonus ?? 0;
                $overtime = $payroll->working_shift_amount ?? 0;
                $net = $payroll->net_salary_before_tax ?? 0;
            @endphp
            <tr>
                <td class="text-center sticky-col sticky-col-1">{{ $loop->iteration }}</td>
                <td class="sticky-col sticky-col-2">{{ $employee->full_name ?? '' }}</td>
                <td class="sticky-col sticky-col-3">{{ $employee->bank_name ?? '' }}</td>
                <td class="text-center sticky-col sticky-col-4">{{ $employee->bank_account ?? '' }}</td>
                <td class="text-center sticky-col sticky-col-5">{{ $payroll->unit_price_v1 ? number_format($payroll->unit_price_v1) : 0 }}</td>

                <td class="text-center">{{ number_format($v1, 2) }}</td>
                @foreach(['position', 'hazard', 'responsibility'] as $type)
                    <td class="text-center">
                        {{ number_format($employee->allowances->where('type', $type)->sum('rate'), 2) }}
                    </td>
                @endforeach
                <td class="text-center">
                    {{ $workingDays }}/{{ $workingDaysRequired }}
                </td>
                <td class="text-end">{{ number_format($v1Salary) }}</td>
                @foreach( $allowances->groupBy('type') as $type => $group)
                    @php
                        $rate = $employee->allowances->where('type', $type)->sum('rate');
                    @endphp
                    <td class="text-end">{{ number_format($rate * $payroll->unit_price_v1) }}</td>
                @endforeach
                @php
                    $totalAllowance = $allowances->groupBy('type')->sum(function($group) use ($employee, $payroll) {
                        $rate = $employee->allowances->where('type', $group->first()->type)->sum('rate');
                        return $rate * ($payroll->unit_price_v1 ?? 0);
                    });

                    $totalDeduction = $deductions->sum(function($deduction) use ($employee, $v1Salary) {
                        $rate = $employee->deductions->where('id', $deduction->id)->sum('rate');
                        return $rate * $v1Salary;
                    });
                @endphp
                <td class="text-end">{{ number_format($totalAllowance) }}</td>
                @foreach($deductions as $deduction)
                    @php
                        $rate = $employee->deductions->where('id', $deduction->id)->sum('rate');
                        $amount = $rate * $v1Salary;
                    @endphp
                    <td class="text-end">{{ number_format($amount) }}</td>
                @endforeach
                <td class="text-end">{{ number_format($totalDeduction) }}</td>
                <td class="text-end">{{ number_format($overtime) }}</td>
                <td class="text-end">{{ number_format($bonus) }}</td>
                <td class="text-end">{{ number_format($net) }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="{{ 5 + 3 + 3 + $deductions->count() + 6 }}" class="text-left text-muted py-3">
                <i class="fas fa-info-circle me-1"></i> Bảng lương tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }} chưa được chốt.
            </td>
        </tr>
    @endif
    </tbody>
</table>
<style>
    .accounting-table {
        border-collapse: separate;
        border-spacing: 0;
        min-width: max-content;
    }

    .accounting-table th,
    .accounting-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .accounting-table th.sticky-col,
    .accounting-table td.sticky-col {
        position: sticky;
        background: white;
        z-index: 2;
        border-right: 1px solid #dee2e6;
    }

    .accounting-table th.sticky-col-1, .accounting-table td.sticky-col-1 {
        left: 0;
        width: 80px;
        z-index: 3;
    }

    .accounting-table th.sticky-col-2, .accounting-table td.sticky-col-2 {
        left: 80px;
        width: 180px;
        z-index: 3;
    }

    .accounting-table th.sticky-col-3, .accounting-table td.sticky-col-3 {
        left: 260px;
        width: 180px;
        z-index: 3;
    }

    .accounting-table th.sticky-col-4, .accounting-table td.sticky-col-4 {
        left: 440px;
        width: 180px;
        z-index: 3;
    }

    .accounting-table th.sticky-col-5, .accounting-table td.sticky-col-5 {
        left: 620px;
        width: 50px;
        z-index: 3;
    }

    .card-body {
        padding-top: 0.5rem !important;
    }

    .table-responsive {
        margin-top: -1px;
    }
</style>
