<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <h6 id="headerMonth" class="mb-0">Hạch toán bảng lương tháng</h6>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                    <button class="btn btn-primary" id="save-btn" style="display: none;">
                                        <i class="fas fa-save"></i> Lưu
                                    </button>
                                    <button class="btn btn-secondary" id="edit-btn">
                                        <i class="fas fa-edit"></i> Chỉnh sửa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row information-journal">
                            <div class="col-md-6 mb-3 d-flex">
                                   <div class="col-12 mb-3 d-flex">
                                       <label for="rate" class="form-label mt-1 w-25">Ngày hạch toán </label>
                                       <input type="date" class="form-control w-50" name="date_journaling" value="{{ $journals->first()->date_journaling ?? now()->toDateString() }}" disabled>
                                   </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 mb-3 d-flex">
                                <label for="rate" class="form-label mt-1 w-25">Diễn giải </label>
                                <textarea name="description" class="form-control w-75" rows="3" disabled>{{ $journals->first()->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="table-responsive" id="accountingTable">
                            @include('page.accounting.journal-table')
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

            const today = new Date();
            const defaultMonth = today.getMonth() + 1;
            const defaultYear = today.getFullYear();
            const defaultDateStr = `${defaultYear}-${defaultMonth.toString().padStart(2, '0')}`;

            monthInput.value = defaultDateStr;
            headerText.textContent = `Hạch toán bảng lương ${defaultMonth.toString().padStart(2, '0')}/${defaultYear}`;

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

                        headerText.textContent = `Hạch toán bảng lương ${month}/${year}`;
                        monthInput.value = `${year}-${month}`;

                        fetch(`/journal/load?month=${monthInput.value}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('accountingTable').innerHTML = data.html;
                                document.querySelector('input[name="date_journaling"]').value = data.date_journaling;
                                document.querySelector('textarea[name="description"]').value = data.description;
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
        document.getElementById('save-btn').addEventListener('click', () => {
            const rows = document.querySelectorAll('#journal-body tr');
            const journalDate = document.querySelector('input[name="date_journaling"]').value;

            const data = Array.from(rows).map(row => {
                const inputs = row.querySelectorAll('input');
                return {
                    date_journaling: journalDate,
                    content: inputs[1].value,
                    debt_account: inputs[2].value,
                    has_account: inputs[3].value,
                    amount: parseInt(inputs[4].value) || 0
                };
            });


            fetch('{{ route('journal.saveJournal') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    month: document.getElementById('monthPicker').value,
                    description: document.querySelector('textarea[name="description"]').value,
                    journals: data
                })
            }).then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        alert('Lưu thành công!');
                        location.reload();
                    } else {
                        alert('Lưu thất bại!');
                        location.reload();
                    }
                });
        });

    </script>
@endsection
