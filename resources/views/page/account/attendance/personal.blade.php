@php
    use App\Models\Employee;
    use Carbon\Carbon;
    use Illuminate\Support\Collection;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@extends('page.account.layouts.main')
@section('contentAccount')
    <div class="page-inner">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6 id="headerMonth" class="mb-0">
                                Bảng công cá nhân tháng {{ Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}
                            </h6>

                            <div class="ms-auto d-flex gap-2 align-items-center">
                                @if($todayState === 'need_checkin')
                                    <button id="btnCheckIn" class="btn btn-success">
                                        <i class="fas fa-sign-in-alt"></i> Check in
                                    </button>
                                @elseif($todayState === 'need_checkout')
                                    <button id="btnCheckOut" class="btn btn-warning">
                                        <i class="fas fa-sign-out-alt"></i> Check out
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-check"></i> Đã chấm công hôm nay
                                    </button>
                                @endif

                                <div class="position-relative d-inline-block">
                                    <input type="hidden" id="monthPicker" value="{{ $month }}">
                                    <button id="btnPickMonth" class="btn btn-outline-secondary">
                                        <i class="fas fa-calendar-alt"></i> Chọn tháng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="calendar-grid border rounded p-3 bg-white shadow-sm">
                                    <div class="row fw-bold text-center border-bottom pb-2 text-primary">
                                        @foreach(['T2','T3','T4','T5','T6','T7','CN'] as $dow)
                                            <div class="col text-uppercase fs-2">{{ $dow }}</div>
                                        @endforeach
                                    </div>

                                    <?php
                                    /** @var Collection $attendanceData */
                                    /** @var Collection $days */
                                    /** @var \Illuminate\Database\Eloquent\Collection|Employee[] $employees */
                                    ?>

                                    @php
                                        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                                        $daysInMonth = $startOfMonth->daysInMonth;
                                        $startWeekDay = $startOfMonth->dayOfWeekIso;
                                        $totalCells = ceil(($daysInMonth + $startWeekDay - 1) / 7) * 7;
                                        $cells = [];

                                        for ($i = 0; $i < $totalCells; $i++) {
                                            $dayNum = $i - ($startWeekDay - 1) + 1;
                                            $valid = $dayNum >= 1 && $dayNum <= $daysInMonth;
                                            $date = $valid ? $startOfMonth->copy()->day($dayNum) : null;

                                            $cells[] = [
                                                'valid' => $valid,
                                                'day' => $valid ? $dayNum : null,
                                                'date' => $valid ? $date : null,
                                                'dateStr' => $valid ? $date->format('Y-m-d') : null,
                                                'isWeekend' => $valid ? in_array($date->dayOfWeekIso, [6,7]) : false,
                                                'hasWork' => $valid ? $attendanceData->contains('work_date', $date->format('Y-m-d')) : false,
                                            ];
                                        }
                                    @endphp

                                    @foreach(array_chunk($cells, 7) as $week)
                                        <div class="row">
                                            @foreach($week as $cell)
                                                <div class="col text-center border p-2 mb-2 calendar-box
                                                    {{ !$cell['valid'] ? 'bg-light text-muted' : '' }}
                                                    {{ $cell['isWeekend'] ? 'text-muted' : '' }}
                                                ">
                                                    @if($cell['valid'])
                                                        <div class="fw-bold">{{ $cell['day'] }}</div>
                                                        @php
                                                            $detail = $cell['valid'] && isset($attendanceMap[$cell['dateStr']])
                                                                ? $attendanceMap[$cell['dateStr']]
                                                                : null;
                                                        @endphp
                                                        @if($cell['isWeekend'])
                                                            <div class="small text-muted mt-3">N</div>
                                                        @elseif($detail)
                                                            @if($detail->is_full_day)
                                                                <div class="text-success mt-3 attendance-cell"
                                                                     data-detail='@json($detail)'>
                                                                    <i class="fas fa-check-circle fa-lg"></i>
                                                                </div>
                                                            @else
                                                                <div class="text-warning mt-3 attendance-cell"
                                                                     data-detail='@json($detail)'>
                                                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="text-danger mt-3">
                                                                <i class="fas fa-times-circle fa-lg"></i>
                                                            </div>
                                                        @endif

                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-primary shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        Tổng hợp tháng
                                    </div>
                                    <div class="card-body">
                                        <p><strong>✔️ Số ngày đi làm:</strong> {{ $workingDays }}</p>
                                        <p><strong>❌ Số ngày nghỉ:</strong> {{ $leaveDays }}</p>
                                        <p><strong>📅 Tổng công thực tế:</strong> {{ $dayWork }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalTitle">Chấm công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="btnCloseModal"></button>
                </div>
                <div class="modal-body">
                    <div class="border rounded p-2">
                        <video id="attVideo" autoplay playsinline style="width:100%; border-radius:8px;"></video>
                    </div>
                    <canvas id="attCanvas" class="d-none"></canvas>

                    <div class="alert d-none mt-3" id="attAlert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="btnCancelCam">
                        Hủy
                    </button>
                    <button type="button" class="btn btn-primary" id="btnSnapAndSend">
                        <i class="fas fa-camera"></i> Chụp & Gửi
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="attendanceDetailModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết chấm công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p><strong>📅 Ngày đi làm:</strong> <span id="ad-date"></span></p>
                    <p><strong>⏰ Check in:</strong> <span id="ad-checkin"></span></p>
                    <p><strong>⏰ Check out:</strong> <span id="ad-checkout"></span></p>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ad-full-day" disabled>
                        <label class="form-check-label">Có đi làm đủ ngày</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ad-late" disabled>
                        <label class="form-check-label">Đi muộn</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ad-early" disabled>
                        <label class="form-check-label">Về sớm</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar-grid .col {
            width: 14.28%;
            min-height: 90px;
            border-radius: 6px;
        }

        .calendar-box {
            transition: background-color 0.3s;
            background-color: #fff;
        }

        .calendar-box:hover {
            background-color: #f1f1f1;
        }

        .flatpickr-calendar {
            top: calc(100% + 5px) !important;
            left: auto !important;
            right: 0 !important;
            z-index: 9999 !important;
        }

        .flatpickr-input[readonly] {
            display: none !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthInput = document.getElementById('monthPicker');
            const btnPick = document.getElementById('btnPickMonth');

            const fp = flatpickr(monthInput, {
                dateFormat: "Y-m",
                defaultDate: monthInput.value,
                appendTo: btnPick.parentElement,
                allowInput: false,
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y"
                })],
                onChange: function (selectedDates) {
                    if (selectedDates.length) {
                        const date = selectedDates[0];
                        const month = (date.getMonth() + 1).toString().padStart(2, '0');
                        const year = date.getFullYear();
                        window.location.href = `/account/attendance/personal/${year}-${month}`;
                    }
                }
            });

            btnPick.addEventListener('click', () => fp.open());
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const CHECKIN_URL  = "{{ route('ai.attendance.checkin') }}";
            const CHECKOUT_URL = "{{ route('ai.attendance.checkout') }}";
            const CSRF = "{{ csrf_token() }}";

            const btnCheckIn  = document.getElementById('btnCheckIn');
            const btnCheckOut = document.getElementById('btnCheckOut');

            const modalEl = document.getElementById('cameraModal');
            const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

            const attVideo = document.getElementById('attVideo');
            const attCanvas = document.getElementById('attCanvas');
            const btnSnapAndSend = document.getElementById('btnSnapAndSend');
            const attAlert = document.getElementById('attAlert');
            const cameraModalTitle = document.getElementById('cameraModalTitle');

            let stream = null;
            let actionType = null;

            function showAlert(type, htmlText) {
                attAlert.className = `alert alert-${type}`;
                attAlert.innerHTML = String(htmlText).replaceAll('\n', '<br>');
                attAlert.classList.remove('d-none');
            }

            function hideAlert() {
                attAlert.classList.add('d-none');
            }

            async function startCamera() {
                hideAlert();
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "user" },
                    audio: false
                });
                attVideo.srcObject = stream;
            }

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(t => t.stop());
                    stream = null;
                }
                attVideo.srcObject = null;
            }

            function captureDataUrl() {
                const w = attVideo.videoWidth;
                const h = attVideo.videoHeight;

                attCanvas.width = w;
                attCanvas.height = h;

                const ctx = attCanvas.getContext('2d');
                ctx.drawImage(attVideo, 0, 0, w, h);

                return attCanvas.toDataURL('image/jpeg', 0.85);
            }

            async function postAttendance(url, snapshot) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ snapshot })
                });

                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Request failed');
                return data;
            }

            async function openForAction(type) {
                actionType = type;
                cameraModalTitle.textContent = type === 'checkin' ? 'Check in' : 'Check out';
                btnSnapAndSend.disabled = false;
                btnSnapAndSend.innerHTML = `<i class="fas fa-camera"></i> Chụp & Gửi`;

                try {
                    await startCamera();
                    modal && modal.show();
                } catch (e) {
                    console.error(e);
                    alert('Không bật được camera. Hãy cấp quyền camera hoặc chạy bằng https/localhost.');
                }
            }

            if (btnCheckIn)  btnCheckIn.addEventListener('click', () => openForAction('checkin'));
            if (btnCheckOut) btnCheckOut.addEventListener('click', () => openForAction('checkout'));

            btnSnapAndSend.addEventListener('click', async () => {
                if (!actionType) return;

                btnSnapAndSend.disabled = true;
                btnSnapAndSend.textContent = 'Đang gửi...';

                try {
                    const snapshot = captureDataUrl();
                    const url = actionType === 'checkin' ? CHECKIN_URL : CHECKOUT_URL;

                    const result = await postAttendance(url, snapshot);

                    const emp = result.employee || {};
                    const t = result.time || {};
                    const title = actionType === 'checkin' ? '✅ Check-in thành công' : '✅ Check-out thành công';

                    const msg =
                        `${title}\n` +
                        `Nhân viên: ${emp.full_name || '-'} (${emp.employee_code || '-'})\n` +
                        `Ngày: ${t.work_date || '-'}\n` +
                        `Giờ vào: ${t.check_in || '-'}\n` +
                        `${t.check_out ? ('Giờ ra: ' + t.check_out + '\n') : ''}` +
                        `${result.worked_hours ? ('Số giờ làm: ' + result.worked_hours) : ''}`;

                    showAlert('success', msg);

                    setTimeout(() => {
                        stopCamera();
                        modal && modal.hide();
                        window.location.reload();
                    }, 1500);
                } catch (e) {
                    console.error(e);
                    showAlert('danger', e.message || 'Có lỗi xảy ra');
                    btnSnapAndSend.disabled = false;
                    btnSnapAndSend.innerHTML = `<i class="fas fa-camera"></i> Chụp & Gửi`;
                }
            });

            modalEl.addEventListener('hidden.bs.modal', () => {
                stopCamera();
                actionType = null;
                hideAlert();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('attendanceDetailModal');
            const modal = new bootstrap.Modal(modalEl);

            document.querySelectorAll('.attendance-cell').forEach(cell => {
                cell.addEventListener('click', () => {
                    const d = JSON.parse(cell.dataset.detail);

                    document.getElementById('ad-date').innerText =
                        new Date(d.work_date).toLocaleDateString('vi-VN');

                    document.getElementById('ad-checkin').innerText =
                        d.check_in ? d.check_in.replace('T', ' ') : '-';

                    document.getElementById('ad-checkout').innerText =
                        d.check_out ? d.check_out.replace('T', ' ') : '-';

                    document.getElementById('ad-full-day').checked = !!d.is_full_day;
                    document.getElementById('ad-late').checked = !!d.is_late;
                    document.getElementById('ad-early').checked = !!d.is_early;

                    modal.show();
                });
            });
        });
    </script>

@endsection
