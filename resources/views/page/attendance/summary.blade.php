@php use Carbon\Carbon; @endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6 id="headerMonth">Bảng công tổng hợp
                                tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="{{ $month }}">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                    <form action="{{ route('accounting.postPayrollTable') }}" method="POST" id="closeAttendanceForm" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="month" id="inputMonthForClose" value="{{ $month }}">
                                        <button class="btn btn-secondary" type="submit">
                                            <i class="fas fa-save"></i> Chốt bảng lương
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="attendanceTable">
                            <table class="table table-bordered table-hover attendance-table">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center" rowspan="2">STT</th>
                                    <th width="10%" class="text-center" rowspan="2">MÃ NV</th>
                                    <th width="13%" class="text-center" rowspan="2">HỌ TÊN</th>
                                    <th width="20%" class="text-center" rowspan="2">CHỨC VỤ</th>
                                    <th width="20%" class="text-center" rowspan="2">Phòng ban</th>
                                    <th class="text-center" colspan="3">Công số của tháng: {{ $dayWork }} </th>
                                </tr>
                                <tr>
                                    <th class="text-center">Số ngày làm việc</th>
                                    <th class="text-center">Số ngày nghỉ</th>
                                    <th class="text-center">Số ca làm thêm</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($attendanceData as $key => $attendance)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center">{{ $attendance->employee->employee_code ?? '' }}</td>
                                        <td>{{ $attendance->employee->full_name ?? '' }}</td>
                                        <td>{{ $attendance->employee->position->name ?? '' }}</td>
                                        <td>{{ $attendance->employee->department->name ?? '' }}</td>
                                        <td class="text-center">{{ $attendance->working_days ?? 0 }}</td>
                                        <td class="text-center">{{ $attendance->leave_days ?? 0 }}</td>
                                        <td class="text-center">{{ $attendance->overtime_hours ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted" style="font-style: italic;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Bảng công tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }} hiện chưa được chốt.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .flatpickr-calendar {
            top: calc(100% + 5px) !important;
            left: auto !important;
            right: 0 !important;
            z-index: 9999 !important;
        }
        .flatpickr-input[readonly] {
            display: none !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthInput = document.getElementById('monthPicker');
            const btnPick = document.getElementById('btnPickMonth');
            const headerText = document.getElementById('headerMonth');

            const fp = flatpickr(monthInput, {
                dateFormat: "Y-m",
                defaultDate: monthInput.value,
                appendTo: btnPick.parentElement,
                allowInput: false,
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })],
                onChange: function (selectedDates, dateStr, instance) {
                    const date = selectedDates[0];
                    if (date) {
                        const month = (date.getMonth() + 1).toString().padStart(2, '0');
                        const year = date.getFullYear();
                        const newMonth = `${year}-${month}`;
                        window.location.href = `/attendance/summary/${newMonth}`;
                    }
                    instance.close();
                }
            });

            btnPick.addEventListener('click', () => {
                fp.open();
            });
        });
    </script>

@endsection
