@php use App\Models\Bonus; @endphp
<div class="modal fade" id="editBonusModal" tabindex="-1" aria-labelledby="editBonusModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editBonusModalLabel">Cập nhật khoản thưởng nhân viên</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editBonusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_method" value="POST">
                    <input type="hidden" id="edit-bonus-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên khoản thưởng</label>
                        <input type="text" id="edit-name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Mô tả</label>
                        <textarea id="edit-description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-amount" class="form-label">Số tiền (VNĐ)</label>
                        <input type="number" id="edit-amount" name="amount" class="form-control" required min="0">
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
