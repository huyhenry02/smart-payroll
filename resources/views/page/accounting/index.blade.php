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
                                <form action="{{ route('accounting.postPayrollTable') }}" method="POST" id="wageForm" class="d-flex align-items-center gap-2 mb-0">
                                    @csrf
                                    <h6 id="wageText" class="mb-0">Đơn giá lương V1: <strong id="wageAmount">{{ number_format($unitPriceV1)  }}VNĐ</strong></h6>
                                    <input type="number" class="form-control" name="unit_price_v1" id="wageInput"
                                           style="width: 150px; display: none;" value="{{ $unitPriceV1 }}">
                                    <input type="hidden" id="monthPicker" value="">
                                    <button type="button" class="btn btn-outline-secondary" id="btnEditWage">Sửa đơn giá</button>
                                    <button type="submit" class="btn btn-outline-success" id="btnSaveWage" style="display: none;">Lưu</button>
                                </form>
                                <div class="position-relative d-inline-block">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn
                                    </button>
                                </div>
                                <a
                                    id="btnPdfPreview"
                                    href=""
                                    target="_blank"
                                    class="btn btn-danger"
                                >
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
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
            headerText.textContent = `Bảng lương tháng ${defaultMonth.toString().padStart(2, '0')}/${defaultYear}`;

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
                        headerText.textContent = `Bảng lương tháng ${month}/${year}`;
                        monthInput.value = `${year}-${month}`;

                        fetch(`/accounting/load?month=${monthInput.value}`)
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('accountingTable').innerHTML = html;
                            });
                        fetch(`/accounting/unit-price?month=${monthInput.value}`)
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('wageAmount').textContent = data.unit_price_v1 + 'VNĐ';
                                document.getElementById('wageInput').value = data.unit_price_v1.replaceAll(',', '');
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
        document.getElementById('btnEditWage').addEventListener('click', function () {
            document.getElementById('wageText').style.display = 'none';
            document.getElementById('wageInput').style.display = 'inline-block';
            this.style.display = 'none';
            document.getElementById('btnSaveWage').style.display = 'inline-block';
        });

    </script>
@endsection
