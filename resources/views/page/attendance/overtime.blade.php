@php use App\Models\Employee;use App\Models\WorkingShift;use Carbon\Carbon; @endphp
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
                            <h6 id="headerMonth">Danh sách ca làm thêm
                                tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="{{ $month }}">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn tháng
                                    </button>
                                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                                            data-bs-target="#createOvertimeModal">
                                        <i class="fa fa-plus"></i> Tạo ca làm thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="attendanceTable">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center">STT</th>
                                    <th class="text-center">MÃ NV</th>
                                    <th >HỌ TÊN</th>
                                    <th >CHỨC VỤ</th>
                                    <th >Phòng ban</th>
                                    <th >Ca làm thêm</th>
                                    <th class="text-center">Ngày làm thêm</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($overtimes as $index => $ot)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $ot->employee->employee_code }}</td>
                                        <td>{{ $ot->employee->full_name }}</td>
                                        <td>{{ $ot->employee->position->name ?? '' }}</td>
                                        <td>{{ $ot->employee->department->name ?? '' }}</td>
                                        <td>{{ WorkingShift::TYPES[$ot->workingShift->type] ?? '' }}</td>
                                        <td class="text-center">{{ Carbon::parse($ot->work_date)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $ot->id }}"
                                                    data-employee="{{ $ot->employee_id }}"
                                                    data-date="{{ $ot->work_date }}"
                                                    data-shift="{{ $ot->working_shift_id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editOvertimeModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('attendance.deleteOvertime', $ot->id) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle me-1"></i> Không có ca làm thêm nào trong tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}.
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
    @include('page.attendance.create-overtime')
    @include('page.attendance.update-overtime')
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
                        window.location.href = `/attendance/overtime/${newMonth}`;
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
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa ca làm thêm này không?')) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const employee = btn.dataset.employee;
                const date = btn.dataset.date;
                const shift = btn.dataset.shift;

                document.getElementById('editOvertimeForm').action = `/attendance/overtime/update/${id}`;
                document.getElementById('editEmployee').value = employee;
                document.getElementById('editDate').value = date;
                document.getElementById('editShift').value = shift;

                const selected = document.querySelector(`#editShift option[value="${shift}"]`);
                document.getElementById('editRate').value = selected?.dataset.rate + ' VNĐ / giờ' ?? '';
            });
        });

        document.getElementById('editShift').addEventListener('change', function () {
            const rate = this.selectedOptions[0]?.dataset.rate || '';
            document.getElementById('editRate').value = rate + ' VNĐ / giờ';
        });
    </script>
@endsection
