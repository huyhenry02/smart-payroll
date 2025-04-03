@php use App\Models\User; @endphp
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
                            placeholder="Tìm kiếm theo tên, số điện thoại, email,..."
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
                            <h6>Danh sách người dùng</h6>
                            <button
                                class="btn btn-primary btn-round ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#createUserModal"
                            >
                                <i class="fa fa-plus"></i>
                                Thêm người dùng
                            </button>
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
                                    <th>Mã NV</th>
                                    <th>Họ và tên</th>
                                    <th>Email</th>
                                    <th>Loại người dùng</th>
                                    <th class="text-center" width="12%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + 1 ?? 'N/A' }}</td>
                                        <td>{{ $user->employee?->employee_code ?? 'N/A' }}</td>
                                        <td>{{ $user->employee?->full_name ?? 'N/A'  }}</td>
                                        <td>{{ $user->email ?? 'N/A'  }}</td>
                                        <td>{{ $user->role ? User::ROLES[$user->role] : 'N/A'  }}</td>
                                        <td class="text-center">
                                            <a href=""
                                               class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
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
                            {!! $users->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('page.system.user.create')
@endsection
