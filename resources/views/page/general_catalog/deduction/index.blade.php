@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách khoản trích nộp theo lương</h6>
                            <button
                                class="btn btn-primary btn-round ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#createDeductionModal"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm khoản trích nộp
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="10%">STT</th>
                                    <th>Tên khoản trích nộp</th>
                                    <th>Tỉ lệ theo lương cơ bản</th>
                                    <th width="15%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $deductions as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->name ?? '' }}</td>
                                        <td>{{ $val->rate ? $val->rate * 100 : '' }}%</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $val->id }}"
                                                    data-name="{{ $val->name ?? '' }}"
                                                    data-rate="{{ $val->rate ? $val->rate * 100 : 0 }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('general_catalog.deleteDeduction', $val->id) }}')">
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
    @include('page.general_catalog.deduction.create')
    @include('page.general_catalog.deduction.update')
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
            const editModal = new bootstrap.Modal(document.getElementById('editDeductionModal'));
            const form = document.getElementById('editDeductionForm');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const rate = this.getAttribute('data-rate');
                    console.log(this.getAttribute('data-rate'))
                    form.setAttribute('action', `/general_catalog/deduction/update/${id}`);
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-rate').value = rate;
                    editModal.show();
                });
            });
        });
    </script>

@endsection
