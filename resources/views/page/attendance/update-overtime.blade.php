@php use App\Models\Employee;use App\Models\WorkingShift; @endphp
<div class="modal fade" id="editOvertimeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editOvertimeForm" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Cập nhật ca làm thêm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_method" value="POST">
                <div class="mb-3">
                    <label>Nhân viên</label>
                    <select name="employee_id" id="editEmployee" class="form-select" required>
                        @foreach(Employee::all() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->employee_code }} - {{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Ngày làm thêm</label>
                    <input type="date" name="work_date" id="editDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Ca làm</label>
                    <select name="working_shift_id" id="editShift" class="form-select" required>
                        <option value="">-- Chọn ca --</option>
                        @foreach($workingShifts as $shift)
                            <option value="{{ $shift->id }}" data-rate="{{ $shift->hourly_rate }}">
                                {{ WorkingShift::TYPES[$shift->type] ?? $shift->type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Tiền công theo giờ</label>
                    <input type="text" id="editRate" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button class="btn btn-warning">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
