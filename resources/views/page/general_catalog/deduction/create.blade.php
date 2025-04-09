<div class="modal fade" id="createDeductionModal" tabindex="-1" aria-labelledby="createDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createDeductionModalLabel">Thêm khoản trích nộp theo lương</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('general_catalog.postDeduction') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên khoản trích nộp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nhập tên khoản trích nộp" required>
                    </div>
                    <div class="mb-3">
                        <label for="rate" class="form-label">Tỉ lệ theo lương cơ bản(%)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="rate" id="rate" placeholder="Nhập tỉ lệ" required>
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
