<div class="modal fade" id="editWorkingShiftModal" tabindex="-1" aria-labelledby="editWorkingShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editWorkingShiftModalLabel">Cập nhật ca làm việc</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editWorkingShiftForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-hourly_rate" class="form-label">Số tiền / giờ làm thêm (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="hourly_rate" id="edit-hourly_rate">
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
