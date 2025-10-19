<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
            </div>

            <form id="formChangePassword" action="{{ route('system.putUser', 0) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 position-relative">
                        <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="password"
                                   placeholder="Nhập mật khẩu mới" required>
                            <span class="input-group-text toggle-password" data-target="new_password">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                        <div class="form-text small text-muted mt-1">
                            Yêu cầu: 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.
                        </div>
                        <div id="passwordStrength" class="small mt-1"></div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                   placeholder="Nhập lại mật khẩu" required>
                            <span class="input-group-text toggle-password" data-target="confirm_password">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                        <div id="confirmMessage" class="small mt-1"></div>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" id="btnSavePassword" class="btn btn-primary" disabled>
                        <i class="fa fa-save"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formChangePassword');
        const newPass = document.getElementById('new_password');
        const confirmPass = document.getElementById('confirm_password');
        const saveBtn = document.getElementById('btnSavePassword');
        const passStrength = document.getElementById('passwordStrength');
        const confirmMsg = document.getElementById('confirmMessage');

        document.querySelectorAll('[data-bs-target="#changePasswordModal"]').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                form.action = `/system/user/${userId}`;
            });
        });

        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const eyeIcon = this.querySelector('i');
                if (target.type === 'password') {
                    target.type = 'text';
                    eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    target.type = 'password';
                    eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });

        const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?{}[\]~]).{8,}$/;

        function validatePassword() {
            const password = newPass.value.trim();
            const confirm = confirmPass.value.trim();
            let valid = true;

            if (!strongPasswordRegex.test(password)) {
                passStrength.textContent = "❌ Mật khẩu chưa đủ mạnh.";
                passStrength.className = "small text-danger";
                valid = false;
            } else {
                passStrength.textContent = "✅ Mật khẩu mạnh.";
                passStrength.className = "small text-success";
            }

            if (confirm.length > 0) {
                if (password !== confirm) {
                    confirmMsg.textContent = "❌ Mật khẩu xác nhận không khớp.";
                    confirmMsg.className = "small text-danger";
                    valid = false;
                } else {
                    confirmMsg.textContent = "✅ Mật khẩu trùng khớp.";
                    confirmMsg.className = "small text-success";
                }
            } else {
                confirmMsg.textContent = "";
            }

            saveBtn.disabled = !(password && confirm && valid);
        }

        newPass.addEventListener('input', validatePassword);
        confirmPass.addEventListener('input', validatePassword);

        const modal = document.getElementById('changePasswordModal');
        modal.addEventListener('hidden.bs.modal', function () {
            newPass.value = '';
            confirmPass.value = '';
            passStrength.textContent = '';
            confirmMsg.textContent = '';
            saveBtn.disabled = true;
        });
    });
</script>
