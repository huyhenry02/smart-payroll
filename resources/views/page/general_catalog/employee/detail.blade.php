<div class="modal fade" id="employeeDetailModal" tabindex="-1" aria-labelledby="employeeDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    Chi tiết nhân viên: <span class="badge bg-warning text-dark ms-2" id="modal-code" style="font-size: 16px;"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body pb-1">
                <div class="row align-items-center mb-3">
                    <div class="col-md-3 text-center">
                        <img id="modal-avatar" src="" alt="Avatar" class="shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-sm-6 mb-1">
                                <label class="text-muted">Họ và tên:</label>
                                <div class="fw-semibold" id="modal-name"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="text-muted">Email:</label>
                                <div id="modal-email"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="text-muted">Chức vụ:</label>
                                <div id="modal-position"></div>
                            </div>
                            <div class="col-sm-6 mb-1">
                                <label class="text-muted">Phòng ban:</label>
                                <div id="modal-department"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="employee-info-row">
                    <div class="employee-info-col">
                        <span class="employee-info-label">Giới tính:</span>
                        <span class="employee-info-value" id="modal-gender"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Điện thoại:</span>
                        <span class="employee-info-value" id="modal-phone"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Ngày sinh:</span>
                        <span class="employee-info-value" id="modal-dob"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Số CMND/CCCD:</span>
                        <span class="employee-info-value" id="modal-identity"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Ngày cấp:</span>
                        <span class="employee-info-value" id="modal-identity-date"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Nơi cấp:</span>
                        <span class="employee-info-value" id="modal-identity-place"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Địa chỉ:</span>
                        <span class="employee-info-value" id="modal-address"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Ngày vào làm:</span>
                        <span class="employee-info-value" id="modal-start-date"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Loại hợp đồng:</span>
                        <span class="employee-info-value" id="modal-contract-type"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Trạng thái làm việc:</span>
                        <span class="employee-info-value" id="modal-status"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Sô người phụ thuộc:</span>
                        <span class="employee-info-value" id="modal-number_of_dependent"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Hệ số lương:</span>
                        <span class="employee-info-value" id="modal-factor"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Thâm niên:</span>
                        <span class="employee-info-value" id="modal-seniority"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Mã số thuế:</span>
                        <span class="employee-info-value" id="modal-tax-code"></span>
                    </div>


                    <div class="employee-info-col">
                        <span class="employee-info-label">Trình độ học vấn:</span>
                        <span class="employee-info-value" id="modal-education"></span>
                    </div>

                    <div class="employee-info-col">
                        <span class="employee-info-label">Ngân hàng:</span>
                        <span class="employee-info-value" id="modal-bank-name"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">Chuyên ngành:</span>
                        <span class="employee-info-value" id="modal-specialization"></span>
                    </div>
                    <div class="employee-info-col">
                        <span class="employee-info-label">STK ngân hàng:</span>
                        <span class="employee-info-value" id="modal-bank-account"></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer pt-2">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<style>
    .employee-info-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -12px;
    }

    .employee-info-col {
        width: 50%;
        padding: 6px 12px;
        font-size: 14px;
    }

    .employee-info-label {
        font-weight: 500;
        color: #6c757d;
        min-width: 140px;
        display: inline-block;
    }

    .employee-info-value {
        font-weight: 600;
        color: #212529;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .employee-info-col {
            width: 100%;
        }
    }
</style>
