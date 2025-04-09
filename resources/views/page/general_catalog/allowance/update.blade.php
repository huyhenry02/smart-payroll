@php use App\Models\Allowance; @endphp
<div class="modal fade" id="editAllowanceModal" tabindex="-1" aria-labelledby="editAllowanceModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editAllowanceModalLabel">Cập nhật khoản phụ cấp, trợ cấp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
            </div>
            <form id="editAllowanceForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-allowance-id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Tên khoản phụ cấp, trợ cấp</label>
                        <input type="text" class="form-control" name="name" id="edit-name"
                               placeholder="Nhập tên phụ cấp, trợ cấp">
                    </div>
                    <div class="mb-3">
                        <label for="edit-type" class="form-label">Loại phụ cấp</label>
                        <select name="type" id="edit-type" class="form-select" required>
                            <option value="">-- Chọn loại phụ cấp --</option>
                            @foreach(Allowance::TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-rate" class="form-label">Tỷ lệ phụ cấp (%)</label>
                        <input type="text" step="0.01" class="form-control" name="rate" id="edit-rate"
                               placeholder="Nhập tỷ lệ phụ cấp">
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
