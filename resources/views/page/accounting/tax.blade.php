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
                            <h6 id="headerMonth"> Bảng tính thuế thu nhập cá nhân tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                    <a
                                        id="btnPdfPreview"
                                        href="{{ route('accounting.previewTaxPdf', $month) }}"
                                        target="_blank"
                                        class="btn btn-danger"
                                    >
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="taxTable">
                            @include('page.accounting.tax-table', ['employees' => $employees, 'month' => $month])
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
            headerText.textContent = `Bảng tính thuế thu nhập cá nhân tháng ${defaultMonth.toString().padStart(2, '0')}/${defaultYear}`;

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
                        const newMonth = `${year}-${month}`;

                        headerText.textContent = `Bảng tính thuế thu nhập cá nhân tháng ${month}/${year}`;
                        monthInput.value = `${year}-${month}`;

                        fetch(`/accounting/load-tax?month=${monthInput.value}`)
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('taxTable').innerHTML = html;
                            });

                        document.getElementById('btnPdfPreview').href = `/accounting/preview-tax-pdf/${newMonth}`;
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
