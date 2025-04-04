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
                            <h6 id="headerMonth">Danh sách thưởng nhân viên theo tháng</h6>
                            <div class="ms-auto">
                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="">
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
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center" rowspan="2">STT</th>
                                    <th width="10%" class="text-center" rowspan="2">Mã NV</th>
                                    <th width="20%" rowspan="2">Họ tên</th>
                                    <th width="20%" rowspan="2" class="text-center">Chức vụ</th>
                                    <th colspan="{{ $bonuses->count() }}" class="text-center">Loại tiền thưởng</th>
                                    <th width="15%" rowspan="2" class="text-center">Số tiền</th>
                                </tr>
                                <tr>
                                    @foreach($bonuses as $key => $val)
                                        <th>{{ $val->name }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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
@endsection
