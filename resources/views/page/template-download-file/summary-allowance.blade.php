@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo phụ cấp, trợ cấp</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 16px;
        }

        .header .date {
            margin-top: 10px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px 6px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #eee;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>
<div class="header">
    <h2>NGÂN HÀNG AGRIBANK</h2>
    <h3>Tổng hợp các khoản phụ cấp, trợ cấp theo nhân viên</h3>
    <div class="date">
        Ngày xuất file: {{ Carbon::now()->format('d/m/Y') }}
    </div>
</div>

<table>
    <thead>
    <tr>
        <th rowspan="2">STT</th>
        <th rowspan="2">Mã NV</th>
        <th rowspan="2">Họ tên</th>
        <th rowspan="2">Chức vụ</th>
        <th colspan="{{ $allowances->count() }}">Các khoản phụ cấp, trợ cấp</th>
    </tr>
    <tr>
        @foreach ($allowances as $allowance)
            <th>{{ $allowance->name }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $index => $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td width="10%">{{ $employee['employee_code'] }}</td>
            <td class="text-left" width="15%">{{ $employee['full_name'] }}</td>
            <td width="15%">{{ $employee['position'] }}</td>
            @foreach ($allowances as $allowance)
                <td>
                    {{ $employee['allowances'][$allowance->name] ? '✓' : '' }}
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
