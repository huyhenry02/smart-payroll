@php use App\Models\Employee;use Carbon\Carbon; use Illuminate\Support\Collection;@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6 id="headerMonth">Bảng công cá nhân
                                tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="{{ $month }}">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn tháng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="calendar-grid border rounded p-3 bg-white shadow-sm">
                                    <div class="row fw-bold text-center border-bottom pb-2 text-primary">
                                        @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $dow)
                                            <div class="col text-uppercase fs-2">{{ $dow }}</div>
                                        @endforeach
                                    </div>
                                    <?php
                                    /** @var Collection $attendanceData */
                                    /** @var Collection $days */
                                    /** @var \Illuminate\Database\Eloquent\Collection|Employee[] $employees */
                                    ?>
                                    @php
                                        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                                        $daysInMonth = $startOfMonth->daysInMonth;
                                        $startWeekDay = $startOfMonth->dayOfWeekIso;
                                        $totalCells = ceil(($daysInMonth + $startWeekDay - 1) / 7) * 7;
                                        $cells = [];

                                        for ($i = 0; $i < $totalCells; $i++) {
                                            $dayNum = $i - ($startWeekDay - 1) + 1;
                                            $valid = $dayNum >= 1 && $dayNum <= $daysInMonth;
                                            $date = $valid ? $startOfMonth->copy()->day($dayNum) : null;
                                            $cells[] = [
                                                'valid' => $valid,
                                                'day' => $valid ? $dayNum : null,
                                                'date' => $valid ? $date : null,
                                                'dateStr' => $valid ? $date->format('Y-m-d') : null,
                                                'isWeekend' => $valid ? in_array($date->dayOfWeekIso, [6,7]) : false,
                                                'hasWork' => $valid ? $attendanceData->contains('work_date', $date->format('Y-m-d')) : false
                                            ];
                                        }
                                    @endphp

                                    @foreach(array_chunk($cells, 7) as $week)
                                        <div class="row">
                                            @foreach($week as $cell)
                                                <div class="col text-center border p-2 mb-2 calendar-box
                                                    {{ !$cell['valid'] ? 'bg-light text-muted' : '' }}
                                                    {{ $cell['isWeekend'] ? 'text-muted' : '' }}
                                                ">
                                                    @if($cell['valid'])
                                                        <div class="fw-bold">{{ $cell['day'] }}</div>
                                                        @if($cell['isWeekend'])
                                                            <div class="small text-muted mt-3">N</div>
                                                        @elseif($cell['hasWork'])
                                                            <div class="text-success mt-3"><i
                                                                    class="fas fa-check-circle fa-lg"></i></div>
                                                        @else
                                                            <div class="text-danger mt-3"><i
                                                                    class="fas fa-times-circle fa-lg"></i></div>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-primary shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        Tổng hợp tháng
                                    </div>
                                    <div class="card-body">
                                        <p><strong>✔️ Số ngày đi làm:</strong> {{ $workingDays }}</p>
                                        <p><strong>❌ Số ngày nghỉ:</strong> {{ $leaveDays }}</p>
                                        <p><strong>📅 Tổng công thực tế:</strong> {{ $dayWork }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .calendar-grid .col {
            width: 14.28%;
            min-height: 90px;
            border-radius: 6px;
        }

        .calendar-box {
            transition: background-color 0.3s;
            background-color: #fff;
        }

        .calendar-box:hover {
            background-color: #f1f1f1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthInput = document.getElementById('monthPicker');
            const btnPick = document.getElementById('btnPickMonth');
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
                onChange: function (selectedDates, dateStr) {
                    if (selectedDates.length) {
                        const date = selectedDates[0];
                        const month = (date.getMonth() + 1).toString().padStart(2, '0');
                        const year = date.getFullYear();
                        window.location.href = `/attendance/personal/${year}-${month}`;
                    }
                }
            });
            btnPick.addEventListener('click', () => fp.open());
        });
    </script>
@endsection
