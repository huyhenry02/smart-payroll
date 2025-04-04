@php use App\Models\Bonus; @endphp
<div class="modal fade" id="editBonusModal" tabindex="-1" aria-labelledby="editBonusModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editBonusModalLabel">Cập nhật khoản thưởng nhân viên</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
            </div>
            <form id="editBonusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="edit-bonus-id">

                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-warning text-white">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
