@php use App\Models\Employee;use App\Models\Payroll;use Illuminate\Database\Eloquent\Collection; @endphp
@php use Carbon\Carbon; @endphp
<table class="display table table-bordered table-hover accounting-table">
    <thead>
    <tr>
        <th class="text-center" >STT</th>
        <th class="text-center"  width="15%">Họ và tên</th>
        <th class="text-center" >Mã số thuế</th>
        <th class="text-center" >Ngân hàng</th>
        <th class="text-center"  width="10%">Tài khoản trích nộp thuế</th>
        <th class="text-center" >Thu nhập tháng</th>
        <th class="text-center" >Thuế suất(%)</th>
        <th class="text-center" >Số thuế phải nộp(VND)</th>
        <th class="text-center" >Lương sau thuế (VND)</th>
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
                <td>{{ $employee->bank_name ?? '' }}</td>
                <td class="text-center">{{ $employee->bank_account }}</td>
                <td class="text-end">{{ $payroll->net_salary_before_tax ? number_format($payroll->net_salary_before_tax) : 0 }}</td>
                <td class="text-end">{{ $payroll->tax_rate ?? 0}}</td>
                <td class="text-end">{{ $payroll->tax_amount ? number_format($payroll->tax_amount) : 0}}</td>
                <td class="text-end">{{ $payroll->net_salary_after_tax ? number_format($payroll->net_salary_after_tax    ) : 0}}</td>

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
