@php use App\Models\Allowance;use App\Models\Deduction;use App\Models\Employee;use App\Models\Payroll;use Illuminate\Database\Eloquent\Collection; @endphp
@php use Carbon\Carbon; @endphp
<table class="display table table-bordered table-hover accounting-table">
    <thead>
    <tr>
        <th class="text-center" rowspan="2">STT</th>
        <th class="text-center" rowspan="2" width="12%">Họ và tên</th>
        <th class="text-center" rowspan="2">Ngày công thực tế</th>
        <th class="text-center" rowspan="2">Lương V1</th>
        <th class="text-center" rowspan="2">Lương làm
            thêm giờ
        </th>
        <th colspan="4" class="text-center">Phụ cấp thưởng</th>
        <th class="text-center" rowspan="2">Bảo hiểm <br>
            (BHXH, BHYT, BHTN)
        </th>
        <th rowspan="2" class="text-center">Thuế TNCN</th>
        <th rowspan="2" class="text-center">Lương thực lĩnh (sau thuế)
        </th>
    </tr>
    <tr>
        @foreach(Allowance::TYPES_REALITY_TEXT as $key => $val)
            <th class="text-center">{{ $val }}</th>
        @endforeach
        <th class="text-center">Thưởng</th>
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
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $employee->full_name ?? '' }}</td>
                <td class="text-center">{{ $workingDays }}/{{ $workingDaysRequired }}</td>
                <td class="text-end">{{ number_format($v1Salary) }}</td>
                <td class="text-end">{{ number_format($overtime) }}</td>
                @foreach( $allowances->groupBy('type') as $type => $group)
                    @php
                        $rate = $employee->allowances->where('type', $type)->sum('rate');
                    @endphp
                    <td class="text-end">{{ number_format($rate * $payroll->unit_price_v1) }}</td>
                @endforeach
                <td class="text-end">{{ number_format($bonus) }}</td>
                @php
                    /** @var Collection|Deduction[] $deductions */
                @endphp
                @php
                    $totalDeductions = $deductions->sum(fn($deduction) => $employee->deductions->where('id', $deduction->id)->sum('rate') * $v1Salary);
                @endphp
                <td class="text-end">{{ number_format($totalDeductions) }}</td>
                <td class="text-end">{{ $payroll->tax_amount ? number_format($payroll->tax_amount) : 0}}</td>
                <td class="text-end">{{ $payroll->net_salary_after_tax ? number_format($payroll->net_salary_after_tax) : 0}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="{{ 5 + 3 + 3 + $deductions->count() + 3 }}" class="text-left text-muted py-3">
                <i class="fas fa-info-circle me-1"></i> Bảng lương
                tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }} chưa được chốt.
            </td>
        </tr>
    @endif
    </tbody>
</table>
