@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm:</label>
                        <input
                            type="text"
                            placeholder="Tìm kiếm theo tên, mã quyền, mô tả,..."
                            class="form-control search-input"
                            id="search-input"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách nhóm quyền</h6>
                            <a
                                class="btn btn-primary btn-round ms-auto"
                                href="{{route('system.showCreateRole')}}"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm nhóm quyền
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="display table table-bordered table-hover"
                            >
                                <thead>
                                <tr>
                                    <th width="3%">STT</th>
                                    <th>Mã quyền</th>
                                    <th>Tên</th>
                                    <th>Mô tả</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center" width="12%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $key => $role)
                                    <tr>
                                        <td>{{ $key + 1 ?? 'N/A' }}</td>
                                        <td>{{ $role->code ?? 'N/A' }}</td>
                                        <td>{{ $role->name ?? 'N/A'  }}</td>
                                        <td>{{ $role->description ?? 'N/A'  }}</td>
                                        <td>
                                            @if( $role->is_active )
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('system.showUpdateRole', $role->id) }}"
                                               class="btn btn-sm btn-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('system.deleteRole', $role->id) }}')"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {!! $roles->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function confirmDelete(url) {
        if (confirm('Bạn có chắc chắn muốn xóa nhóm quyền này không?')) {
            window.location.href = url;
        }
    }
</script>
