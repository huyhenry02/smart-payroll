@php use App\Models\Allowance; @endphp
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách khoản phụ cấp, trợ cấp</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th rowspan="2">STT</th>
                                    <th rowspan="2">Họ và tên</th>
                                    <th rowspan="2">Số tài khoản</th>
                                    <th colspan="5" class="text-center">Hệ số lương cơ bản</th>
                                    <th rowspan="2">Lương V1</th>
                                    <th colspan="3" class="text-center">Các khoản phụ cấp thành tiền</th>
                                    <th colspan="{{ $deductions->count() }}" class="text-center">Các khoản trích nộp thành tiền</th>
                                    <th rowspan="2" class="text-center">Thực lĩnh</th>
                                </tr>
                                <tr>
                                    <th>V1</th>
                                    @foreach(Allowance::TYPES_REALITY_CODE as $key => $val)
                                        <th>{{ $val }}</th>
                                    @endforeach
                                    <th>N.Lương</th>
                                    @foreach(Allowance::TYPES_REALITY_TEXT as $key => $val)
                                    <th>{{ $val }}</th>
                                    @endforeach
                                    @foreach($deductions as $key => $val)
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
@endsection
