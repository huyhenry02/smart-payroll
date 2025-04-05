@php use App\Models\Employee;use Illuminate\Support\Collection; @endphp
<table class="table table-bordered table-hover attendance-table">
    <thead>
    <tr>
        <th class="text-center sticky-col sticky-col-1" rowspan="2">STT</th>
        <th class="text-center sticky-col sticky-col-2" rowspan="2">MÃ NV</th>
        <th class="text-center sticky-col sticky-col-3" rowspan="2">HỌ TÊN</th>
        <th class="text-center sticky-col sticky-col-4" rowspan="2">CHỨC VỤ</th>
        @foreach($days as $day)
            <th class="text-center">{{ $day['day'] }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach($days as $day)
            <th class="text-center">
                @php
                    $weekdayMap = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
                    echo $weekdayMap[$day['weekday'] - 1];
                @endphp
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $index => $employee)
        <tr>
            <td class="text-center sticky-col sticky-col-1">{{ $index + 1 }}</td>
            <td class="text-center sticky-col sticky-col-2">{{ $employee->employee_code }}</td>
            <td class="sticky-col sticky-col-3">{{ $employee->full_name }}</td>
            <td class="sticky-col sticky-col-4">{{ $employee->position->name ?? '' }}</td>
            @foreach( $days as $day)
                @php
                    /** @var Collection $attendanceData */
                    /** @var Collection $days */
                    /** @var \Illuminate\Database\Eloquent\Collection|Employee[] $employees */
                @endphp
                @php
                    $employeeAttendance = $attendanceData->get($employee->id, collect());
                    $att = $employeeAttendance->firstWhere('work_date', $day['date']);
                    $isSunday = $day['weekday'] === 7;
                    $isSaturday = $day['weekday'] === 6;
                @endphp
                <td class="text-center">
                    @if($isSunday || $isSaturday)
                        <span class="text-muted">N</span>
                    @else
                        @php
                            $checked = $att ? 'checked' : '';
                        @endphp
                        <span class="text-display {{ $checked ? 'text-success' : 'text-danger' }}">
                            {!! $checked ? '✔' : '✖' !!}
                        </span>
                        <input
                            type="checkbox"
                            class="form-check-input d-none attendance-checkbox"
                            data-employee="{{ $employee->id }}"
                            data-date="{{ $day['date'] }}"
                            {{ $checked }}>
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
<style>
    .attendance-table {
        border-collapse: separate;
        border-spacing: 0;
        min-width: max-content;
    }

    .attendance-table th,
    .attendance-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .attendance-table th.sticky-col,
    .attendance-table td.sticky-col {
        position: sticky;
        background: white;
        z-index: 2;
        border-right: 1px solid #dee2e6;
    }

    .attendance-table th.sticky-col-1, .attendance-table td.sticky-col-1 {
        left: 0;
        width: 60px;
        z-index: 3;
    }

    .attendance-table th.sticky-col-2, .attendance-table td.sticky-col-2 {
        left: 60px;
        width: 100px;
        z-index: 3;
    }

    .attendance-table th.sticky-col-3, .attendance-table td.sticky-col-3 {
        left: 160px;
        width: 180px;
        z-index: 3;
    }

    .attendance-table th.sticky-col-4, .attendance-table td.sticky-col-4 {
        left: 340px;
        width: 180px;
        z-index: 3;
    }

    .card-body {
        padding-top: 0.5rem !important;
    }

    .table-responsive {
        margin-top: -1px;
    }

</style>
<script>
    const btnEdit = document.getElementById('btnEdit');
    const btnSave = document.getElementById('btnSave');
    const btnClose = document.getElementById('btnClose');

    btnEdit.addEventListener('click', () => {
        document.querySelectorAll('.text-display').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.attendance-checkbox').forEach(el => el.classList.remove('d-none'));
        btnEdit.classList.add('d-none');
        btnClose.classList.add('d-none');
        btnSave.classList.remove('d-none');
    });

    btnSave.addEventListener('click', () => {
        const checkedList = [];
        const uncheckedList = [];

        document.querySelectorAll('.attendance-checkbox').forEach(cb => {
            const empId = cb.dataset.employee;
            const date = cb.dataset.date;
            const isChecked = cb.checked;

            (isChecked ? checkedList : uncheckedList).push({ employee_id: empId, work_date: date });
        });

        fetch("{{ route('attendance.detail-attendance.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ checked: checkedList, unchecked: uncheckedList })
        })
            .then(res => res.json())
            .then(data => {
                alert('Cập nhật thành công!');
                location.reload();
            })
            .catch(err => {
                alert('Đã xảy ra lỗi khi lưu!');
            });
    });
</script>
