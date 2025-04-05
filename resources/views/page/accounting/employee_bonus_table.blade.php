@php use App\Models\Employee;use Illuminate\Support\Collection; @endphp
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th class="text-center" rowspan="2">STT</th>
        <th class="text-center" rowspan="2">MÃ NV</th>
        <th class="text-center" rowspan="2">HỌ TÊN</th>
        <th class="text-center" rowspan="2">CHỨC VỤ</th>
        <th class="text-center" colspan="{{ $bonuses->count() }}">Các khoản thưởng</th>
        <th class="text-center" rowspan="2">Số tiền nhận (VNĐ)</th>
    </tr>
    <tr>
        @foreach( $bonuses as $bonus )
            <th class="text-center">{{ $bonus->name }}</th>
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
            @php $total = 0; @endphp

            @foreach( $bonuses as $bonus)
                @php
                    /** @var Collection $employeeBonuses */
                    /** @var Collection $bonus */
                    /** @var \Illuminate\Database\Eloquent\Collection|Employee[] $employees */
                @endphp
                @php
                    $employeeBonusesData = $employeeBonuses->get($employee->id, collect());
                    $att = $employeeBonusesData->firstWhere('bonus_id', $bonus->id);
                @endphp
                <td class="text-center">
                    @php
                        $checked = $att ? 'checked' : '';
                    @endphp
                    <span class="text-display {{ $checked ? 'text-success' : 'text-danger' }}">
                            {!! $checked ? '✔' : '✖' !!}
                        </span>
                    <input
                        type="checkbox"
                        class="form-check-input d-none bonuses-checkbox"
                        data-employee="{{ $employee->id }}"
                        data-bonus="{{ $bonus->id }}"
                        {{ $checked }}>

                </td>
                @if ($checked)
                    @php $total += $bonus->amount; @endphp
                @endif

            @endforeach
            <td class="text-center">{{ number_format($total) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    const btnEdit = document.getElementById('btnEdit');
    const btnSave = document.getElementById('btnSave');

    btnEdit.addEventListener('click', () => {
        document.querySelectorAll('.text-display').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.bonuses-checkbox').forEach(el => el.classList.remove('d-none'));
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
    });

    btnSave.addEventListener('click', () => {
        const checkedList = [];
        const uncheckedList = [];
        const month = document.getElementById('monthPicker').value;

        document.querySelectorAll('.bonuses-checkbox').forEach(cb => {
            const empId = cb.dataset.employee;
            const bonusId = cb.dataset.bonus;
            const isChecked = cb.checked;

            const data = { employee_id: empId, bonus_id: bonusId };
            (isChecked ? checkedList : uncheckedList).push(data);
        });

        fetch("{{ route('accounting.updateEmployeeBonus') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                month: month,
                checked: checkedList,
                unchecked: uncheckedList
            })
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
