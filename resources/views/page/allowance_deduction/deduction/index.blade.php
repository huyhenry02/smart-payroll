@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Tổng hợp khoản trích nộp theo nhân viên</h6>
                            <div class="ms-auto">
                                <button class="btn btn-primary" id="save-btn" style="display: none;">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                                <button class="btn btn-secondary" id="edit-btn">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                                <a
                                    href="{{ route('allowance_deduction.previewDeductionPdf') }}"
                                    target="_blank"
                                    class="btn btn-danger"
                                >
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center" rowspan="2">STT</th>
                                    <th width="10%" class="text-center" rowspan="2">Mã NV</th>
                                    <th width="15%" rowspan="2">Họ tên</th>
                                    <th width="10%" rowspan="2" class="text-center">Chức vụ</th>
                                    <th class="text-center" colspan="{{ $deductions->count() }}">Các Khoản trích nộp
                                    </th>
                                </tr>
                                <tr>
                                    @foreach( $deductions as $key => $val)
                                        <th class="text-center">{{ $val->name ?? '' }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $data as $key => $employee)

                                    <tr>
                                        <td class="text-center">
                                            {{ $key }}
                                        </td>
                                        <td class="text-center">
                                            {{ $employee['employee_code'] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employee['full_name'] ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $employee['position'] ?? '' }}
                                        </td>
                                        @foreach ($deductions as $deduction)
                                            @php
                                                $isChecked = $employee['deductions'][$deduction->name];
                                            @endphp
                                            <td class="text-center">
                                                <span
                                                    class="text-display {{ $isChecked ? 'text-success' : 'text-danger' }}">
                                                    {{ $isChecked ? '✔' : '✖' }}
                                                </span>
                                                <input
                                                    type="checkbox"
                                                    name="deductions[{{ $employee['id'] }}][{{ $deduction->id }}]"
                                                    class="form-check-input d-none deduction-checkbox"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                >
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('edit-btn').addEventListener('click', function () {
            document.querySelectorAll('.text-display').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.deduction-checkbox').forEach(el => el.classList.remove('d-none'));

            document.getElementById('edit-btn').style.display = 'none';
            document.getElementById('save-btn').style.display = 'inline-block';
        });

        document.getElementById('save-btn').addEventListener('click', function () {
            const formData = new FormData();

            document.querySelectorAll('.deduction-checkbox').forEach(checkbox => {
                if (checkbox.checked) {
                    const name = checkbox.getAttribute('name');
                    formData.append(name, true);
                }
            });

            fetch("{{ route('allowance_deduction.putDeduction') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Lưu thành công!");
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra!");
                    }
                });
        });
    </script>
@endsection
