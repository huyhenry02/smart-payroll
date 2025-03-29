@php use App\Models\WorkingShift; @endphp
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách ca làm việc</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="10%">STT</th>
                                    <th width="40%">Tên ca làm việc</th>
                                    <th>Số tiền / giờ làm thêm (VNĐ)</th>
                                    <th width="15%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $workingShifts as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->type ? WorkingShift::TYPES[$val->type] : '' }}</td>
                                        <td>{{ $val->hourly_rate ? number_format($val->hourly_rate) : '' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $val->id }}"
                                                    data-hourly_rate="{{ $val->hourly_rate }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editWorkingShiftModal"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('general_catalog.deleteWorkingShift', $val->id) }}')"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
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
    @include('page.general_catalog.working_shift.update')
    <script>
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa ca làm việc này không?')) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editModal = new bootstrap.Modal(document.getElementById('editWorkingShiftModal'));
            const form = document.getElementById('editWorkingShiftForm');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const hourly_rate = this.getAttribute('data-hourly_rate');
                    const id = this.getAttribute('data-id');
                    form.setAttribute('action', `/general_catalog/working-shift/update/${id}`);
                    document.getElementById('edit-hourly_rate').value = hourly_rate;
                    editModal.show();
                });
            });
        });
    </script>
@endsection
