@php use App\Models\Payroll; @endphp
@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thuế thu nhập cá nhân háng {{ Carbon::createFromFormat('Y-m', $data['month'])->format('m/Y') }} </title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm 25mm 20mm 25mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 0.5px solid #000;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
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

        .title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
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

        .bold {
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: end;
        }
    </style>
</head>
<body>
<table class="header-table">
    <tr>
        <td class="center">
            <div class="bold">AGRIBANK VIỆT NAM</div>
            <div class="bold">Chi nhánh:</div>
        </td>
        <td class="center">
            <div class="bold">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
            <div class="bold">Độc lập - Tự do - Hạnh phúc</div>
        </td>
    </tr>
</table>
<div class="title">BẢNG TÍNH THUẾ THU NHẬP CÁ NHÂN THÁNG {{ Carbon::createFromFormat('Y-m', $data['month'])->format('m/Y') }}</div>
<table class="">
    <thead>
    <tr>
        <th rowspan="3">STT</th>
        <th rowspan="3" width="15%">Họ và tên</th>
        <th rowspan="3">Mã số thuế</th>
        <th rowspan="3">Thu nhập tháng</th>
        <th colspan="3">Các khoản giảm trừ</th>
        <th rowspan="3">Tổng cộng</th>
        <th rowspan="3" width="10%">Thu nhập tính thuế</th>
        <th rowspan="3">Số thuế phải nộp</th>
        <th rowspan="3" width="10%">Tài khoản trích nộp thuế</th>
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
        $employees = $data['employees'];
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
            <td colspan="11" class="text-left text-muted py-3">
                <i class="fas fa-info-circle me-1"></i> Bảng lương tháng {{ Carbon::createFromFormat('Y-m', $data['month'])->format('m/Y') }} chưa được chốt.
            </td>
        </tr>
    @endif
    </tbody>
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
            <strong>Người lập bảng</strong> <br>
            ( Ký, ghi rõ họ tên )
        </td>
        <td style="text-align: center;">
        </td>
        <td style="text-align: center;">
            <strong>Giám đốc</strong> <br>
            ( Ký, ghi rõ họ tên )
        </td>
    </tr>
</table>
</body>
</html>
