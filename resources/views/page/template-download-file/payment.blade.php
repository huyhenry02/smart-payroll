
@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng thanh toán lương tháng {{ Carbon::createFromFormat('Y-m', $data['month'])->format('m/Y') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 12mm 10mm 12mm;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            padding: 5px 12px;
        }
        .bold {
            font-weight: bold;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
            font-weight: bold;
            font-size: 13px;
        }
        .header-table td {
            border: none;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            line-height: 1.6;
            height: 60px;
        }
        .header-table .center {
            text-align: center;
            vertical-align: middle;
        }

        .header-table .left {
            text-align: left;
        }

        .header-table .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 0.5px solid #000;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 10.5px;
            line-height: 1.3;
            word-break: break-word;
        }

        th {
            font-weight: bold;
            white-space: normal;
        }
        .signature {
            margin-top: 20px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: center;
            font-size: 12px;
        }
        th[colspan="4"] {
            text-align: center;
        }
        .table-data th:nth-child(1), td:nth-child(1) { width: 6%; }      /* STT */
        .table-data th:nth-child(2), td:nth-child(2) { width: 15%; }     /* Họ tên */
        .table-data th:nth-child(3), td:nth-child(3) { width: 7%; }      /* Ngày công */
        .table-data th:nth-child(4), td:nth-child(4) { width: 9%; }      /* Lương V1 */
        .table-data th:nth-child(5), td:nth-child(5) { width: 11%; }      /* Làm thêm */
        .table-data th:nth-child(6), td:nth-child(6),
        .table-data th:nth-child(7), td:nth-child(7),
        .table-data th:nth-child(8), td:nth-child(8),
        .table-data th:nth-child(9), td:nth-child(9) { width: 11%; }      /* Các phụ cấp + thưởng */
        .table-data th:nth-child(10), td:nth-child(10) { width: 7%; }    /* Bảo hiểm */
        .table-data th:nth-child(11), td:nth-child(11) { width: 5%; }    /* Thuế */
        .table-data th:nth-child(12), td:nth-child(12) { width: 12%; }   /* Lương thực lĩnh */
        .table-data th:nth-child(13), td:nth-child(13) { width: 3.5%; }  /* Ký nhận */

        .table-data td.full_name {
            white-space: nowrap !important;
            text-align: left;
        }



    </style>

</head>
<body>
<table class="header-table">
    <tr>
        <td class="center">
            <div class="bold">NGÂN HÀNG AGRIBANK VIỆT NAM</div>
            <div class="bold"> Chi nhánh:</div>
        </td>
        <td class="right">
            <div class="bold">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div class="bold" style="margin-right: 30px" >Độc lập - Tự do - Hạnh phúc</div>
        </td>
    </tr>
</table>

<h2>BẢNG THANH TOÁN TIỀN LƯƠNG</h2>
<h3>Tháng {{ Carbon::createFromFormat('Y-m', $data['month'])->format('m') }} Năm {{ Carbon::createFromFormat('Y-m', $data['month'])->format('Y') }}</h3>

<table class="table-data">
    <thead>
    <tr>
        <th rowspan="2">STT</th>
        <th rowspan="2">Họ và tên</th>
        <th rowspan="2">Ngày công thực tế</th>
        <th rowspan="2">Lương V1</th>
        <th rowspan="2">Lương làm thêm giờ</th>
        <th colspan="4">Phụ cấp, thưởng</th>
        <th rowspan="2">Bảo hiểm (BHXH, BHYT, BHTN)</th>
        <th rowspan="2">Thuế TNCN</th>
        <th rowspan="2">Lương thực lĩnh (sau thuế)</th>
        <th rowspan="2">Ký nhận</th>
    </tr>
    <tr>
        <th>Phụ cấp chức vụ</th>
        <th>Phụ cấp độc hại</th>
        <th>Phụ cấp trách nhiệm</th>
        <th>Thưởng</th>
    </tr>
    </thead>
    @php
        $totalSalaryV1 = 0;
        $totalOvertime = 0;
        $totalAllowances = ['position' => 0, 'hazard' => 0, 'responsibility' => 0];
        $totalBonus = 0;
        $totalDeductions = 0;
        $totalTax = 0;
        $totalNetAfterTax = 0;
    @endphp

    <tbody>
    @php
        $employees = $data['employees'];
        $hasPayroll = $employees->filter(fn($e) => $e->payrolls->isNotEmpty())->isNotEmpty();
    @endphp

    @if ($hasPayroll)
        @foreach($employees as $index => $employee)
            @php
                $payroll = $employee->payrolls->first();
                $attendance = $employee->attendance->first();
                $workingDays = $attendance->working_days ?? 0;
                $v1 = $employee->salary_factor;
                $v1Salary = $payroll->salary_v1 ?? 0;
                $bonus = $payroll->total_bonus ?? 0;
                $overtime = $payroll->working_shift_amount ?? 0;
                $netAfterTax = $payroll->net_salary_after_tax ?? 0;
                $tax = $payroll->tax_amount ?? 0;

                $totalSalaryV1 += $v1Salary;
                $totalOvertime += $overtime;
                $totalBonus += $bonus;
                $totalTax += $tax;
                $totalNetAfterTax += $netAfterTax;

                foreach (['position', 'hazard', 'responsibility'] as $type) {
                    $totalAllowances[$type] += number_format($employee->allowances->where('type', $type)->sum('rate'), 2) * $v1Salary;
                }

                $deductionTotal = $data['deductions']->sum(fn($deduction) => $employee->deductions->where('id', $deduction->id)->sum('rate') * $v1Salary);
                $totalDeductions += $deductionTotal;
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="full_name">{{ $employee->full_name ?? '' }}</td>
                <td>{{ $workingDays }}/{{ $data['workingDaysRequired'] }}</td>
                <td class="text-end">{{ number_format($v1Salary) }}</td>
                <td class="text-end">{{ number_format($overtime) }}</td>

                @foreach( $data['allowances']->groupBy('type') as $type => $group)
                    @php
                        $rate = $employee->allowances->where('type', $type)->sum('rate');
                    @endphp
                    <td class="text-end">{{ number_format($rate * $payroll->unit_price_v1) }}</td>
                @endforeach

                <td class="text-end">{{ number_format($bonus) }}</td>
                <td class="text-end">{{ number_format($deductionTotal) }}</td>
                <td class="text-end">{{ number_format($tax) }}</td>
                <td class="text-end">{{ number_format($netAfterTax) }}</td>
                <td></td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="13" style="text-align: left;">Không có dữ liệu bảng lương.</td>
        </tr>
    @endif
    </tbody>

    <tfoot>
    <tr>
        <td colspan="3"><strong>Tổng cộng :</strong></td>
        <td class="text-end"><strong>{{ number_format($totalSalaryV1) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalOvertime) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalAllowances['position']) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalAllowances['hazard']) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalAllowances['responsibility']) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalBonus) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalDeductions) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalTax) }}</strong></td>
        <td class="text-end"><strong>{{ number_format($totalNetAfterTax) }}</strong></td>
        <td></td>
    </tr>
    </tfoot>
</table>

<table class="signature">
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center;">
            Nam Định, ngày {{ Carbon::now()->format('d') }} tháng {{ Carbon::now()->format('m') }} năm {{ Carbon::now()->format('Y') }}<br>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <strong>Người lập biểu</strong> <br>
            ( Ký, ghi rõ họ tên )
        </td>
        <td style="text-align: center;">
            <strong>Kế toán trưởng</strong> <br>
            ( Ký, ghi rõ họ tên )
        </td>
        <td style="text-align: center;">
            <strong>Giám đốc</strong> <br>
            ( Ký, ghi rõ họ tên )
        </td>
    </tr>
</table>

</body>
</html>
