@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ route('general_catalog.putEmployee', $user->employee->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header"><h4>Cập nhật thông tin nhân viên</h4></div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="avatar-upload d-flex justify-content-center">
                                    <label for="avatarInput" class="avatar-preview"
                                           style="width: 150px; height: 150px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; position: relative;">
                                        <input type="file" id="avatarInput" class="d-none" name="avatar"
                                               accept="image/*" onchange="previewImage(event)">
                                        <img id="avatarPreview"
                                             src="{{ $user->employee->avatar ?? '' }}"
                                             alt="Avatar Preview"
                                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; {{ $user->employee->avatar ? 'display: block;' : 'display: none;' }}">
                                        <div id="uploadIcon"
                                             style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 24px; color: #ccc; {{ $user->employee->avatar ? 'display: none;' : 'display: block;' }}">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Mã nhân viên</label>
                                        <input type="text" name="employee_code" class="form-control" value="{{ $user->employee->employee_code }}" readonly>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" name="full_name" class="form-control" value="{{ $user->employee->full_name }}">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->employee->phone }}">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Phòng ban</label>
                                        <select name="department_id" class="form-select">
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $user->employee->department_id == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">Chức vụ</label>
                                        <select name="position_id" class="form-select">
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ $user->employee->position_id == $position->id ? 'selected' : '' }}>
                                                    {{ $position->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @php $e = $user->employee; @endphp

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="male" {{ $e->gender === 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ $e->gender === 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ $e->gender === 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="dob" class="form-control" value="{{ $e->dob }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số CMND/CCCD</label>
                                <input type="text" name="identity_number" class="form-control" value="{{ $e->identity_number }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày cấp</label>
                                <input type="date" name="identity_issued_date" class="form-control" value="{{ $e->identity_issued_date }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nơi cấp</label>
                                <input type="text" name="identity_issued_place" class="form-control" value="{{ $e->identity_issued_place }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" value="{{ $e->address }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày vào làm</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $e->start_date }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tình trạng làm việc</label>
                                <select name="employment_status" class="form-select">
                                    <option value="working" {{ $e->employment_status === 'working' ? 'selected' : '' }}>Đang làm việc</option>
                                    <option value="resigned" {{ $e->employment_status === 'resigned' ? 'selected' : '' }}>Đã nghỉ</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại hợp đồng</label>
                                <input type="text" name="contract_type" class="form-control" value="{{ $e->contract_type }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hệ số lương</label>
                                <input type="number" step="0.01" name="salary_factor" class="form-control" value="{{ $e->salary_factor }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thâm niên</label>
                                <input type="number" name="seniority" class="form-control" value="{{ $e->seniority }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã số thuế</label>
                                <input type="text" name="tax_code" class="form-control" value="{{ $e->tax_code }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số tài khoản ngân hàng</label>
                                <input type="text" name="bank_account" class="form-control" value="{{ $e->bank_account }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngân hàng</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ $e->bank_name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trình độ học vấn</label>
                                <input type="text" name="education_level" class="form-control" value="{{ $e->education_level }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số người phụ thuộc</label>
                                <input type="number" name="number_of_dependent" class="form-control" value="{{ $e->number_of_dependent }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Chuyên ngành</label>
                                <input type="text" name="specialization" class="form-control" value="{{ $e->specialization }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-action text-end mt-3">
                    <button class="btn btn-outline-secondary" type="button" onclick="window.history.back()">Hủy</button>
                    <button class="btn btn-primary" type="submit">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var output = document.getElementById('avatarPreview');
                var uploadIcon = document.getElementById('uploadIcon');
                output.src = reader.result;
                output.style.display = 'block';
                uploadIcon.style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
