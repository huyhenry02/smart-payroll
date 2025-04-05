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
                            <h6 id="headerMonth">Bảng công chi tiết
                                tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="{{ $month }}">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                </div>
                                <button class="btn btn-outline-info" id="btnEdit">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="btn btn-outline-primary d-none" id="btnSave">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                                <form action="{{ route('attendance.postCloseAttendance') }}" method="POST" id="closeAttendanceForm" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="month" id="inputMonthForClose">
                                    <button type="submit" class="btn btn-outline-primary" id="btnClose">
                                        <i class="fas fa-save"></i> Chốt công
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="attendanceTable">
                            @include('page.attendance.detail-table', ['days' => $days, 'employees' => $employees, 'attendanceData' => $attendanceData])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .flatpickr-calendar {
            top: calc(100% + 5px) !important;
            left: 0 !important;
            right: auto !important;
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

            const today = new Date();
            const defaultMonth = today.getMonth() + 1;
            const defaultYear = today.getFullYear();
            const defaultDateStr = `${defaultYear}-${defaultMonth.toString().padStart(2, '0')}`;

            monthInput.value = defaultDateStr;
            headerText.textContent = `Bảng công chi tiết tháng ${defaultMonth.toString().padStart(2, '0')}/${defaultYear}`;

            const fp = flatpickr(monthInput, {
                dateFormat: "Y-m",
                defaultDate: defaultDateStr,
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
                        headerText.textContent = `Bảng công chi tiết tháng ${month}/${year}`;
                        monthInput.value = `${year}-${month}`;

                        fetch(`/attendance/detail-attendance/load?month=${monthInput.value}`)
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('attendanceTable').innerHTML = html;
                            });
                    }
                    instance.close();
                }
            });

            btnPick.addEventListener('click', () => {
                fp.open();
            });
        });
    </script>
    <script>
        document.getElementById('closeAttendanceForm').addEventListener('submit', function (e) {
            document.getElementById('inputMonthForClose').value = document.getElementById('monthPicker').value;
        });
    </script>
@endsection
