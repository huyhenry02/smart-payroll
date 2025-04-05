@php use App\Models\Allowance; @endphp
@extends('layouts.main')
@section('content')
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6>Tổng hợp khoản phụ cấp, trợ cấp theo nhân viên</h6>
                            <div class="ms-auto">
                                <button class="btn btn-primary" id="save-btn" style="display: none;">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                                <button class="btn btn-secondary" id="edit-btn">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                                <a
                                    href="{{ route('allowance_deduction.previewAllowancePdf') }}"
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
                                    <th class="text-center sticky-col left-0" rowspan="3">STT</th>
                                    <th class="text-center sticky-col left-1" rowspan="3">MÃ NV</th>
                                    <th class="sticky-col left-2" rowspan="3">HỌ TÊN</th>
                                    <th class="text-center sticky-col left-3" rowspan="3">CHỨC VỤ</th>
                                    <th class="text-center" colspan="{{ $allowances->count() }}">CÁC KHOẢN PHỤ CẤP, TRỢ
                                        CẤP
                                    </th>
                                </tr>
                                <tr>
                                    @foreach( Allowance::TYPES as $key => $val)
                                        @php
                                            $grouped = $allowances->groupBy('type');
                                            $colspan = isset($grouped[$key]) ? $grouped[$key]->count() : 0;
                                        @endphp
                                        @if( $colspan > 0)
                                            <th class="text-center" colspan="{{ $colspan }}">{{ $val }}</th>
                                        @endif
                                    @endforeach

                                </tr>
                                <tr>
                                    @foreach( $allowances as $key => $val)
                                        <th class="text-center">{{ $val->name ?? '' }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $data as $key => $employee)

                                    <tr>
                                        <td class="text-center sticky-col left-0">{{ $loop->iteration }}</td>
                                        <td class="text-center sticky-col left-1">{{ $employee['employee_code'] }}</td>
                                        <td class="sticky-col left-2">{{ $employee['full_name'] }}</td>
                                        <td class="text-center sticky-col left-3">{{ $employee['position'] }}</td>
                                        @foreach ( $allowances as $allowance)
                                            @php
                                                $isChecked = $employee['allowances'][$allowance->name];
                                            @endphp
                                            <td class="text-center">
                                                <span
                                                    class="text-display {{ $isChecked ? 'text-success' : 'text-danger' }}">
                                                    {{ $isChecked ? '✔' : '✖' }}
                                                </span>
                                                <input
                                                    type="checkbox"
                                                    name="allowances[{{ $employee['id'] }}][{{ $allowance->id }}]"
                                                    class="form-check-input d-none allowance-checkbox"
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
    <style>
        .sticky-col {
            position: sticky;
            background-color: #fff;
            z-index: 2;
            border-right: 1px solid #dee2e6;
        }

        .left-0 {
            left: 0;
            z-index: 3;
            min-width: 50px;
        }

        .left-1 {
            left: 60px;
            min-width: 80px;
        }

        .left-2 {
            left: 140px;
            min-width: 150px;
        }

        .left-3 {
            left: 290px;
            min-width: 120px;
        }

        th, td {
            min-width: 100px;
            white-space: nowrap;
        }

        th.sticky-col, td.sticky-col {
            background-clip: padding-box;
        }
    </style>
    <script>
        document.getElementById('edit-btn').addEventListener('click', function () {
            document.querySelectorAll('.text-display').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.allowance-checkbox').forEach(el => el.classList.remove('d-none'));

            document.getElementById('edit-btn').style.display = 'none';
            document.getElementById('save-btn').style.display = 'inline-block';
        });

        document.getElementById('save-btn').addEventListener('click', function () {
            const formData = new FormData();

            document.querySelectorAll('.allowance-checkbox').forEach(checkbox => {
                if (checkbox.checked) {
                    const name = checkbox.getAttribute('name');
                    formData.append(name, true);
                }
            });

            fetch("{{ route('allowance_deduction.putAllowance') }}", {
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
