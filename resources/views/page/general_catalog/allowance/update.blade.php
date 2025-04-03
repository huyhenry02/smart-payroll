<div class="modal fade" id="editAllowanceModal" tabindex="-1" aria-labelledby="editAllowanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editAllowanceModalLabel">Cập nhật khoản phụ cấp, trợ cấp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editAllowanceForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-allowance-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên khoản phụ cấp, trợ cấp</label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nhập tên phụ cấp, trợ cấp">
                    </div>
                    <div class="mb-3">
                        <label for="edit-amount" class="form-label">Số tiền (VNĐ)</label>
                        <input type="number" class="form-control" name="amount" id="edit-amount" placeholder="Nhập Số tiền">
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
