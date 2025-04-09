<div class="modal fade" id="editDeductionModal" tabindex="-1" aria-labelledby="editDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editDeductionModalLabel">Cập nhật khoản trích nộp theo lương</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editDeductionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-deduction-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên khoản trích nộp</label>
                        <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nhập tên phòng ban">
                    </div>
                    <div class="mb-3">
                        <label for="edit-rate" class="form-label">Tỉ lệ theo lương cơ bản(%)</label>
                        <input type="text" class="form-control" name="rate" id="edit-rate" placeholder="Nhập tỉ lệ">
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
