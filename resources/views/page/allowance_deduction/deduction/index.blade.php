@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Tổng hợp khoản trích nộp theo nhân viên</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center" rowspan="2">STT</th>
                                    <th width="10%" class="text-center" rowspan="2">Mã NV</th>
                                    <th width="15%" rowspan="2">Họ tên</th>
                                    <th width="10%" rowspan="2" class="text-center">Chức vụ</th>
                                    <th class="text-center" colspan="{{ $deductions->count() }}">Các Khoản trích nộp</th>
                                </tr>
                                <tr>
                                    @foreach( $deductions as $key => $val)
                                        <th class="text-center">{{ $val->name ?? '' }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $employee)

                                    <tr>
                                        <td class="text-center">
                                            {{ $key }}
                                        </td>
                                        <td class="text-center">
                                            {{ $employee['employee_code'] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employee['full_name'] ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $employee['position'] ?? '' }}
                                        </td>
                                        @foreach ($deductions as $deduction)
                                            <td class="text-center {{ $employee['deductions'][$deduction->name] ? 'text-success' : 'text-danger' }}">
                                                {{ $employee['deductions'][$deduction->name] ? '✓' : 'X' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
