<div class="modal fade" id="createAllowanceModal" tabindex="-1" aria-labelledby="createAllowanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createAllowanceModalLabel">Thêm khoản phụ cấp, trợ cấp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('general_catalog.postAllowance') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên khoản phụ cấp, trợ cấp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên khoản phụ cấp, trợ cấp" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Số tiền (VNĐ)<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount" id="amount" placeholder="Nhập số tiền" required>
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
