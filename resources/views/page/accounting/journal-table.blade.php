@php use Carbon\Carbon; @endphp
<table class="display table table-bordered table-hover accounting-table">
    <thead>
    <tr>
        <th class="text-center" width="6%">STT</th>
        <th class="text-center" width="15%">Ngày hạch toán</th>
        <th class="text-center" width="30%">Nội dung</th>
        <th class="text-center" width="17%">Tài khoản nợ</th>
        <th class="text-center" width="17%">Tài khoản có</th>
        <th class="text-center" width="15%">Số tiền</th>
        <th class="text-center edit-mode d-none"></th>

    </tr>
    </thead>
    <tbody id="journal-body">
    @if( $journals->count())
        @foreach( $journals as $index => $journal)
            <tr data-id="{{ $journal->id }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    <span class="view-mode text-center">{{ $journal->date_journaling ? Carbon::createFromFormat('Y-m-d', $journal->date_journaling)->format('d-m-Y') : '' }}</span>
                    <input type="date" class="form-control edit-mode d-none" value="{{ $journal->date_journaling }}">
                </td>
                <td>
                    <span class="view-mode">{{ $journal->content ?? '' }}</span>
                    <input type="text" class="form-control edit-mode d-none" value="{{ $journal->content }}">
                </td>
                <td>
                    <span class="view-mode">{{ $journal->debt_account ?? '' }}</span>
                    <input type="text" class="form-control edit-mode d-none" value="{{ $journal->debt_account }}">
                </td>
                <td>
                    <span class="view-mode">{{ $journal->has_account ?? ''}}</span>
                    <input type="text" class="form-control edit-mode d-none" value="{{ $journal->has_account }}">
                </td>
                <td class="text-end">
                    <span class="view-mode">{{ number_format($journal->amount) ?? 0 }}</span>
                    <input type="number" class="form-control edit-mode d-none" value="{{ $journal->amount }}">
                </td>
                <td class="text-center edit-mode d-none">
                    <button class="btn btn-danger btn-remove"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center text-muted fst-italic">Tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }} chưa có dữ liệu hạch toán</td>
        </tr>
    @endif
    </tbody>
    <tfoot>
    <tr>
        <td colspan="7" class="text-end">
            <button id="add-row-btn" class="btn btn-sm btn-success" style="display: none;">
                <i class="fas fa-plus"></i> Thêm dòng
            </button>
        </td>
    </tr>
    </tfoot>
</table>
<script>
    let isEditMode = false;
    document.getElementById('edit-btn').addEventListener('click', () => {
        isEditMode = true;
        const placeholderRow = document.querySelector('#journal-body tr td[colspan]');
        if (placeholderRow) {
            placeholderRow.closest('tr').remove();
        }
        document.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));
        document.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
        document.getElementById('edit-btn').style.display = 'none';
        document.getElementById('save-btn').style.display = 'inline-block';
        document.querySelectorAll('#journal-body input, .btn-remove').forEach(el => el.disabled = false);
        document.querySelector('input[name="date_journaling"]').disabled = false;
        document.querySelector('textarea[name="description"]').disabled = false;
        document.getElementById('add-row-btn').style.display = 'inline-block';
    });

    document.getElementById('add-row-btn').addEventListener('click', () => {
        const tbody = document.getElementById('journal-body');
        const rowCount = tbody.rows.length + 1;
        const newRow = document.createElement('tr');
        const dateValue = document.querySelector('input[name="date_journaling"]').value;
        newRow.innerHTML = `
        <td class="text-center">${rowCount}</td>
        <td><input type="date" class="form-control" value="${dateValue}"></td>
        <td><input type="text" class="form-control"></td>
        <td><input type="text" class="form-control"></td>
        <td><input type="text" class="form-control"></td>
        <td><input type="number" class="form-control"></td>
        <td class="text-center">
            <button class="btn btn btn-danger btn-remove"><i class="fas fa-trash"></i></button>
        </td>
    `;
        tbody.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            e.target.closest('tr').remove();
        }
    });
</script>
