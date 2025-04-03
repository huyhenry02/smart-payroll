<div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editPositionModalLabel">Cập nhật chức vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editPositionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-position-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên chức vụ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nhập tên chức vụ" required>
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
