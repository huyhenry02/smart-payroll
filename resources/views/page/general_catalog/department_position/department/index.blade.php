@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách phòng ban</h6>
                            <button
                                class="btn btn-primary btn-round ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#createDepartmentModal"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm phòng ban
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="10%">STT</th>
                                    <th width="20%">Tên phòng ban</th>
                                    <th>Mô tả</th>
                                    <th width="15%">SL nhân viên</th>
                                    <th width="15%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $departments as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->name ?? '' }}</td>
                                        <td>{{ $val->description ?? '' }}</td>
                                        <td>{{ $val->employees()->count() ?? 0 }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $val->id }}"
                                                    data-name="{{ $val->name }}"
                                                    data-description="{{ $val->description }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('general_catalog.deleteDepartment', $val->id) }}')">
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
    @include('page.general_catalog.department_position.department.create')
    @include('page.general_catalog.department_position.department.update')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa phòng ban này không?')) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editModal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
            const form = document.getElementById('editDepartmentForm');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const description = this.getAttribute('data-description');
                    form.setAttribute('action', `/general_catalog/department/update/${id}`);
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-description').value = description;
                    editModal.show();
                });
            });
        });
    </script>

@endsection
