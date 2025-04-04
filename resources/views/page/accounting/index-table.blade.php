@php use App\Models\Allowance;use App\Models\Payroll; @endphp
<table class="display table table-bordered table-hover">
    <thead>
    <tr>
        <th rowspan="2">STT</th>
        <th rowspan="2">Họ và tên</th>
        <th rowspan="2">Ngân hàng</th>
        <th rowspan="2">Số tài khoản</th>
        <th colspan="5" class="text-center">Hệ số lương cơ bản</th>
        <th rowspan="2" class="text-center">Lương V1</th>
        <th colspan="3" class="text-center">Các khoản phụ cấp thành tiền</th>
        <th colspan="{{ $deductions->count() }}" class="text-center">Các khoản trích nộp thành tiền</th>
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
    <tbody>
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
            <td>{{ $index + 1 }}</td>
            <td>{{ $employee->full_name ?? '' }}</td>
            <td>{{ $employee->bank_name ?? '' }}</td>
            <td>{{ $employee->bank_account ?? '' }}</td>

            <td class="text-center">{{ number_format($v1, 2) }}</td>
            @foreach(['position', 'region', 'responsibility'] as $type)
                <td class="text-center">
                    {{ number_format($employee->allowances->where('type', $type)->sum('rate'), 2) }}
                </td>
            @endforeach
            <td class="text-center">
                {{ $workingDays }}/{{ $workingDaysRequired }}
            </td>

            <td class="text-end">{{ number_format($v1Salary) }}</td>

            @foreach($allowances->groupBy('type') as $type => $group)
                @php
                    $rate = $employee->allowances->where('type', $type)->sum('rate');
                @endphp
                <td class="text-end">{{ number_format($rate * Payroll::BASE_SALARY) }}</td>
            @endforeach

            @foreach($deductions as $deduction)
                @php
                    $rate = $employee->deductions->where('id', $deduction->id)->sum('rate');
                    $amount = $rate * $v1Salary;
                @endphp
                <td class="text-end">{{ number_format($amount) }}</td>
            @endforeach

            <td class="text-end">{{ number_format($overtime) }}</td>
            <td class="text-end">{{ number_format($bonus) }}</td>
            <td class="text-end">{{ number_format($net) }}</td>
        </tr>
    @endforeach
    </tbody>

</table>
