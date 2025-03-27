<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editDepartmentModalLabel">Cập nhật phòng ban</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editDepartmentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-department-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nhập tên phòng ban" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" id="edit-description" rows="3" placeholder="Nhập mô tả (nếu có)"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-warning text-white">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
