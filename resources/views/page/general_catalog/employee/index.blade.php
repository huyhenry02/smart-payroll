@php use App\Models\Employee;
 use App\Models\User; @endphp
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm:</label>
                        <input
                            type="text"
                            placeholder="Tìm kiếm theo tên, số điện thoại, email,..."
                            class="form-control search-input"
                            id="search-input"
                        />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Trạng thái làm việc:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Chọn trạng thái</option>
                            @foreach(Employee::STATUS_LIST as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Loại nhân viên:</label>
                        <select class="form-control" id="role" name="role">
                            <option value="">Chọn nhân viên</option>
                            @foreach( User::ROLES as $key => $role)
                                <option value="{{ $key }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Phòng ban:</label>
                        <select class="form-control" id="department" name="department">
                            <option value="">Chọn phòng ban</option>
                            @foreach( $departments as $key => $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label">Chức vụ:</label>
                        <select class="form-control" id="position" name="position">
                            <option value="">Chọn chức vụ</option>
                            @foreach( $positions as $key => $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Danh sách nhân viên</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="display table table-bordered table-hover" id="employee-table"
                            >
                                <thead>
                                <tr>
                                    <th width="2%">STT</th>
                                    <th width="8%">Mã NV</th>
                                    <th width="12%">Họ và tên</th>
                                    <th>Email</th>
                                    <th>Loại NV</th>
                                    <th width="10%">Ngày sinh</th>
                                    <th>Phòng ban</th>
                                    <th>Chức vụ</th>
                                    <th class="text-center" width="12%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $users as $key => $user )
                                    <tr>
                                        @php
                                            $employee = $user->employee;
                                        @endphp
                                        <td>{{ $key + 1 ?? 'N/A' }}</td>
                                        <td>{{ $employee->employee_code ?? 'N/A' }}</td>
                                        <td>{{ $employee->full_name ?? 'N/A'  }}</td>
                                        <td>{{ $user->email ?? 'N/A'  }}</td>
                                        <td>{{ $user->role ? User::ROLES[$user->role] : 'N/A'  }}</td>
                                        <td>{{ $employee->dob ?? 'N/A'  }}</td>
                                        <td>{{ $employee->department->name ?? 'N/A'  }}</td>
                                        <td>{{ $employee->position->name ?? 'N/A'  }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('general_catalog.showUpdateEmployee', $user->id) }}"
                                               class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                class="btn btn-sm btn-info btn-view-employee"
                                                data-bs-toggle="modal"
                                                data-bs-target="#employeeDetailModal"
                                                data-avatar="{{ $employee->avatar ?? '/assets/img/no_avatar.jpg' }}"
                                                data-name="{{ $employee->full_name ?? '' }}"
                                                data-email="{{ $user->email ?? '' }}"
                                                data-phone="{{ $employee->phone ?? ''}}"
                                                data-position="{{ $employee->position->name ?? 'N/A' }}"
                                                data-department="{{ $employee->department->name ?? 'N/A' }}"
                                                data-code="{{ $employee->employee_code?? '' }}"
                                                data-gender="{{ $employee->gender ? Employee::GENDERS[$employee->gender] : '' }}"
                                                data-dob="{{ $employee->dob ?? '' }}"
                                                data-identity="{{ $employee->identity_number ?? '' }}"
                                                data-identity-date="{{ $employee->identity_issued_date ?? '' }}"
                                                data-identity-place="{{ $employee->identity_issued_place ?? '' }}"
                                                data-address="{{ $employee->address ?? '' }}"
                                                data-start-date="{{ $employee->start_date ?? '' }}"
                                                data-contract-type="{{ $employee->contract_type ?? '' }}"
                                                data-status="{{ $employee->employment_status ? Employee::STATUS_LIST[$employee->employment_status] : '' }}"
                                                data-base-salary="{{ $employee->base_salary ?? '' }}"
                                                data-factor="{{ $employee->salary_factor ?? '' }}"
                                                data-seniority="{{ $employee->seniority ?? '' }}"
                                                data-tax-code="{{ $employee->tax_code ?? '' }}"
                                                data-bank-account="{{ $employee->bank_account ?? '' }}"
                                                data-bank-name="{{ $employee->bank_name ?? '' }}"
                                                data-education="{{ $employee->education_level ?? '' }}"
                                                data-specialization="{{ $employee->specialization ?? '' }}"
                                                data-number_of_dependent="{{ $employee->number_of_dependent ?? '' }}"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {!! $users->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('page.general_catalog.employee.detail')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.btn-view-employee', function () {
            const btn = $(this);

            $('#modal-avatar').attr('src', btn.data('avatar'));
            $('#modal-name').text(btn.data('name'));
            $('#modal-email').text(btn.data('email'));
            $('#modal-phone').text(btn.data('phone'));
            $('#modal-position').text(btn.data('position'));
            $('#modal-department').text(btn.data('department'));
            $('#modal-code').text(btn.data('code'));
            $('#modal-gender').text(btn.data('gender'));
            $('#modal-dob').text(btn.data('dob'));
            $('#modal-identity').text(btn.data('identity'));
            $('#modal-identity-date').text(btn.data('identity-date'));
            $('#modal-identity-place').text(btn.data('identity-place'));
            $('#modal-address').text(btn.data('address'));
            $('#modal-start-date').text(btn.data('start-date'));
            $('#modal-contract-type').text(btn.data('contract-type'));
            $('#modal-status').text(btn.data('status'));
            $('#modal-base-salary').text(btn.data('base-salary'));
            $('#modal-factor').text(btn.data('factor'));
            $('#modal-seniority').text(btn.data('seniority'));
            $('#modal-tax-code').text(btn.data('tax-code'));
            $('#modal-bank-account').text(btn.data('bank-account'));
            $('#modal-bank-name').text(btn.data('bank-name'));
            $('#modal-education').text(btn.data('education'));
            $('#modal-specialization').text(btn.data('specialization'));
            $('#modal-number_of_dependent').text(btn.data('number_of_dependent'));
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#search-input, #status, #position, #department, #role').on('input', function () {
                var keySearch = $('#search-input').val();
                var status = $('#status').val();
                var position = $('#position').val();
                var department = $('#department').val();
                var role = $('#role').val();
                $.ajax({
                    url: '{{ route('general_catalog.searchEmployee') }}',
                    method: 'GET',
                    data: {
                        keySearch: keySearch,
                        status: status,
                        position: position,
                        department: department,
                        role: role,
                    },
                    success: function (response) {
                        $('#employee-table tbody').html(response);
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });
    </script>
@endsection
