@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách khoản phụ cấp, trợ cấp</h6>
                            <button
                                class="btn btn-primary btn-round ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#createAllowanceModal"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm khoản phụ cấp, trợ cấp
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="10%">STT</th>
                                    <th>Tên khoản phụ cấp, trợ cấp</th>
                                    <th>Số tiền (VNĐ)</th>
                                    <th width="15%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $allowances as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->name ?? '' }}</td>
                                        <td>{{ $val->amount ? number_format($val->amount) : '' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $val->id }}"
                                                    data-name="{{ $val->name ?? '' }}"
                                                    data-amount="{{ $val->amount ?? 0 }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('general_catalog.deleteAllowance', $val->id) }}')">
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
    @include('page.general_catalog.allowance.create')
    @include('page.general_catalog.allowance.update')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa khoản trích nộp này không?')) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editModal = new bootstrap.Modal(document.getElementById('editAllowanceModal'));
            const form = document.getElementById('editAllowanceForm');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const amount = this.getAttribute('data-amount');
                    form.setAttribute('action', `/general_catalog/allowance/update/${id}`);
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-amount').value = amount;
                    editModal.show();
                });
            });
        });
    </script>

@endsection
