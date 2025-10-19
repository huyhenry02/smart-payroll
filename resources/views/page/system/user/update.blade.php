@php
    use App\Models\Role;
    use App\Models\User;
    $roles = Role::all();
@endphp
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateUserModalLabel">Sửa người dùng</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <form id="formUpdateUser" action="{{ route('system.putUser', 0) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="update_full_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="update_email" required>
                    </div>

                    <div class="mb-3">
                        <label for="role_type" class="form-label">Loại người dùng <span class="text-danger">*</span></label>
                        <select class="form-control" id="update_role_type" name="role" required>
                            <option value="">Chọn loại</option>
                            @foreach(User::ROLES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="role_id" class="form-label">Vai trò hệ thống <span class="text-danger">*</span></label>
                        <select class="form-control" id="update_role_id" name="role_id" required>
                            <option value="">Chọn vai trò</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="update_is_active" name="is_active" value="1">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formUpdateUser');
        let currentUserId = null;
        let cachedData = null;

        document.querySelectorAll('[data-bs-target="#updateUserModal"]').forEach(button => {
            button.addEventListener('click', async function () {
                currentUserId = this.dataset.id;
                form.action = `/system/user/${currentUserId}`;

                try {
                    const res = await fetch(`/system/user/${currentUserId}`);
                    if (!res.ok) throw new Error('Không thể tải thông tin người dùng');
                    cachedData = await res.json();
                } catch (error) {
                    alert(error.message);
                }
            });
        });

        const modal = document.getElementById('updateUserModal');
        modal.addEventListener('shown.bs.modal', function () {
            if (!cachedData) return;
            console.log('cachedData', cachedData);
            document.getElementById('update_full_name').value = cachedData.full_name ?? '';
            document.getElementById('update_email').value = cachedData.email ?? '';
            document.getElementById('update_role_type').value = cachedData.role ?? '';
            document.getElementById('update_role_id').value = cachedData.role_id ?? '';
            document.getElementById('update_is_active').checked = !!cachedData.is_active;
        });
    });
</script>

