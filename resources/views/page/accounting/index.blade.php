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
                            <h6 id="headerMonth" class="mb-0">Bảng lương tháng</h6>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <input type="text" id="monthPicker" value="" readonly style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;">
                                <div class="position-relative d-inline-block">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="accountingTable">
                            @include('page.accounting.index-table')
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
            const defaultDateStr = `${defaultYear}-${String(defaultMonth).padStart(2, '0')}`;

            monthInput.value = defaultDateStr;
            headerText.textContent = `Bảng lương tháng ${String(defaultMonth).padStart(2, '0')}/${defaultYear}`;

            const fp = flatpickr(monthInput, {
                dateFormat: "Y-m",
                defaultDate: defaultDateStr,
                appendTo: btnPick.parentElement,
                allowInput: false,
                clickOpens: false,
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })],
                onChange: function (selectedDates, dateStr, instance) {
                    const date = selectedDates[0];
                    if (!date) return;

                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const ym = `${year}-${month}`;

                    headerText.textContent = `Bảng lương tháng ${month}/${year}`;
                    monthInput.value = ym;

                    fetch(`/accounting/load?month=${encodeURIComponent(ym)}`)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('accountingTable').innerHTML = html;
                        })
                        .catch(() => { /* optional: handle error */ });

                    instance.close();
                }
            });

            btnPick.addEventListener('click', () => fp.open());
        });
    </script>
@endsection
