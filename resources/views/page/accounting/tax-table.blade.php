@php use App\Models\Employee;use App\Models\Payroll;use Illuminate\Database\Eloquent\Collection; @endphp
@php use Carbon\Carbon; @endphp
<table class="display table table-bordered table-hover accounting-table">
    <thead>
    <tr>
        <th class="text-center" rowspan="3">STT</th>
        <th class="text-center" rowspan="3" width="15%">Họ và tên</th>
        <th class="text-center" rowspan="3">Mã số thuế</th>
        <th class="text-center" rowspan="3">Thu nhập tháng</th>
        <th class="text-center" colspan="3">Các khoản giảm trừ</th>
        <th class="text-center" rowspan="3">Tổng cộng</th>
        <th class="text-center" rowspan="3">Thu nhập tính thuế</th>
        <th class="text-center" rowspan="3">Số thuế phải nộp</th>
        <th class="text-center" rowspan="3" width="10%">Tài khoản trích nộp thuế</th>
    </tr>
    <tr>
        <th class="text-center" rowspan="2">Bản thân <br>(11.000.000/1 người)</th>
        <th class="text-center" colspan="2">Người phụ thuộc</th>
    </tr>
    <tr>
        <th class="text-center">Số lượng</th>
        <th class="text-center">Số tiền <br> (4.400.000/ 1 người)</th>
    </tr>
    </thead>
    <tbody>
    @php
        /** @var Collection|Employee[] $employees */
    @endphp
    @php
        $hasPayroll = $employees->filter(fn($e) => $e->payrolls->isNotEmpty())->isNotEmpty();
    @endphp
    @if ( $hasPayroll )
        @foreach($employees as $index => $employee)
            @php
                $payroll = $employee->payrolls->first();
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $employee->tax_code }}</td>
                <td class="text-end">{{ $payroll->net_salary_before_tax ? number_format($payroll->net_salary_before_tax) : 0 }}</td>
                <td class="text-end">{{ Payroll::TAX_SELF ? number_format(Payroll::TAX_SELF) : 0 }}</td>
                <td class="text-end">{{ $employee->number_of_dependent ?? 0 }}</td>
                <td class="text-end">{{ $employee->number_of_dependent ? $employee->number_of_dependent * Payroll::TAX_DEPENDENT : 0 }}</td>
                <td class="text-end">{{ Payroll::TAX_SELF + $employee->number_of_dependent * Payroll::TAX_DEPENDENT }}</td>
                <td class="text-end">{{ $payroll->net_salary_before_tax - (Payroll::TAX_SELF + $employee->number_of_dependent * Payroll::TAX_DEPENDENT) }}</td>
                <td class="text-end">{{ $payroll->tax_amount ? number_format($payroll->tax_amount) : 0}}</td>
                <td class="text-center">{{ $employee->bank_account }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10" class="text-left text-muted py-3">
                <i class="fas fa-info-circle me-1"></i> Bảng lương tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }} chưa được chốt.
            </td>
        </tr>
    @endif
    </tbody>
</table>
