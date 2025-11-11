@php use App\Models\Role; @endphp
@extends('layouts.main')
@section('content')
    <div class="container-fluid py-3">
        <form action="{{ route('system.postCreateRole') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Thông tin nhóm quyền</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên nhóm quyền</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       placeholder="Nhập tên nhóm quyền" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" rows="3" class="form-control"
                                          placeholder="Mô tả ngắn về nhóm quyền..."></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fa fa-save"></i> Lưu nhóm quyền
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    @foreach($permissionsByModule as $module => $permissions)
                        @php
                            $title = Role::TITLES[$module] ?? '';
                        @endphp

                        <div class="card mb-3 border-primary shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-primary fw-bold">
                                    <i class="fa fa-folder-open"></i> {{ $title }}
                                </h6>
                                <div>
                                    <input type="checkbox" class="form-check-input me-1 check-all-module"
                                           id="checkAll_{{ $module }}">
                                    <label for="checkAll_{{ $module }}" class="form-check-label fw-semibold">
                                        Toàn quyền
                                    </label>
                                </div>
                            </div>

                            <div class="card-body py-2">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       name="role_permissions[]"
                                                       value="{{ $permission['id'] }}"
                                                       class="form-check-input permission-checkbox check-{{ $module }}"
                                                       id="perm_{{ $permission['id'] }}">
                                                <label for="perm_{{ $permission['id'] }}" class="form-check-label">
                                                    {{ $permission['name'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.check-all-module').forEach(function (master) {
            master.addEventListener('change', function () {
                const module = this.id.replace('checkAll_', '');
                const checkboxes = document.querySelectorAll('.check-' + module);
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    });
</script>
