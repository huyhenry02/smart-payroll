@php use App\Models\Allowance; @endphp
<div class="modal fade" id="createAllowanceModal" tabindex="-1" aria-labelledby="createAllowanceModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createAllowanceModalLabel">Thêm khoản phụ cấp, trợ cấp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
            </div>
            <form action="{{ route('general_catalog.postAllowance') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên khoản phụ cấp, trợ cấp <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name"
                               placeholder="Nhập tên khoản phụ cấp, trợ cấp" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Loại phụ cấp <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">-- Chọn loại phụ cấp --</option>
                            @foreach(Allowance::TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rate" class="form-label">Tỷ lệ phụ cấp (%) <span
                                class="text-danger">*</span></label>
                        <input type="text" step="0.01" class="form-control" name="rate" id="rate"
                               placeholder="Nhập tỷ lệ phụ cấp" required>
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
