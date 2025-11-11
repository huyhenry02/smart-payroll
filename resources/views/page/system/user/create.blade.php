@php
    use App\Models\Role;use App\Models\User;
    $roles = Role::all();
@endphp
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createUserModalLabel">Thêm người dùng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
            </div>
            <form action="{{ route('system.postUser') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="full_name"
                               placeholder="Họ và tên người dùng" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Nhập email"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Loại người dùng <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Chọn nhân viên</option>
                            @foreach( User::ROLES as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Chọn vai trò</option>
                            @foreach( $roles as $key => $val)
                                <option value="{{ $val->id }}">{{ $val->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="is_active"
                               name="is_active"
                               value="1"
                               checked>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Trạng thái hoạt động
                        </label>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
