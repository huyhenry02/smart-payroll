@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách khoản thưởng nhân viên</h6>
                            <button
                                class="btn btn-primary btn-round ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#createBonusModal"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm khoản thưởng nhân viên
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="25%">Tên khoản thưởng nhân viên</th>
                                    <th>Mô tả</th>
                                    <th width="20%" class="text-center">Số tiền (VNĐ)</th>
                                    <th width="15%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $bonuses as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->name ?? '' }}</td>
                                        <td>{{ $val->description ?? '' }}</td>
                                        <td class="text-center">{{ $val->amount ?  number_format($val->amount) : '' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-secondary btn-edit"
                                                    data-id="{{ $val->id }}"
                                                    data-name="{{ $val->name ?? '' }}"
                                                    data-description="{{ $val->description ?? '' }}"
                                                    data-amount="{{ $val->amount ?? 0 }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('general_catalog.deleteBonus', $val->id) }}')">
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
    @include('page.general_catalog.bonus.create')
    @include('page.general_catalog.bonus.update')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(url) {
            if (confirm('Bạn có chắc chắn muốn xóa khoản thưởng này không này không?')) {
                window.location.href = url;
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.btn-edit').click(function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let description = $(this).data('description');
                let amount = $(this).data('amount');

                $('#edit-bonus-id').val(id);
                $('#edit-name').val(name);
                $('#edit-description').val(description);
                $('#edit-amount').val(amount);

                let formAction = '{{ route('general_catalog.putBonus', ':id') }}'.replace(':id', id);
                $('#editBonusForm').attr('action', formAction);

                $('#editBonusModal').modal('show');
            });
        });
    </script>

@endsection
