@extends('layouts.main')
@section('content')
    <form id="enrollForm" method="POST" action="{{ route('ai.face.enroll.store', $employee->id) }}">
        @csrf
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Đăng ký khuôn mặt (AI) - Chụp từ camera</h5>
                <small class="text-muted">Chụp đủ 10 ảnh (thẳng, nghiêng trái/phải) để tăng độ chính xác. Không tải ảnh lên.</small>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div><strong>Nhân viên:</strong> {{ $employee->full_name }} (<strong>Mã nhân viên</strong>: {{ $employee->employee_code }})</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-2">
                            <video id="faceVideo" autoplay playsinline style="width:100%; border-radius:8px;"></video>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-outline-primary" id="btnStartCamera">
                                <i class="fas fa-video"></i> Bật camera
                            </button>

                            <button type="button" class="btn btn-primary" id="btnCapture" disabled>
                                <i class="fas fa-camera"></i> Chụp ảnh
                            </button>

                            <button type="button" class="btn btn-outline-danger" id="btnClear" disabled>
                                Xóa tất cả
                            </button>

                            <button type="button" class="btn btn-outline-secondary" id="btnStopCamera" disabled>
                                Tắt camera
                            </button>
                        </div>

                        <div class="mt-2">
                            <span class="badge bg-info" id="captureCount">0/10 ảnh</span>
                            <span class="text-muted ms-2" id="cameraStatus">Chưa bật camera</span>
                        </div>

                        <canvas id="faceCanvas" class="d-none"></canvas>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-2" style="min-height: 240px;">
                            <div class="row g-2" id="faceThumbs"></div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * Hệ thống sẽ gửi 10 ảnh này sang AI service để tạo embedding và lưu Qdrant.
                        </small>
                    </div>
                </div>

                <div id="faceInputs"></div>
                <input type="hidden" name="do_face_enroll" id="doFaceEnroll" value="0">

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-success" id="btnSubmitEnroll" disabled>
                        <i class="fas fa-save"></i> Gửi đăng ký (10 ảnh)
                    </button>
                </div>

                <div class="mt-3">
                    <div id="enrollAlert" class="alert d-none" role="alert"></div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const MAX_PHOTOS = 10;

        const form = document.getElementById('enrollForm');

        const video = document.getElementById('faceVideo');
        const canvas = document.getElementById('faceCanvas');
        const thumbs = document.getElementById('faceThumbs');
        const inputsWrap = document.getElementById('faceInputs');

        const btnStart = document.getElementById('btnStartCamera');
        const btnStop = document.getElementById('btnStopCamera');
        const btnCapture = document.getElementById('btnCapture');
        const btnClear = document.getElementById('btnClear');
        const btnSubmit = document.getElementById('btnSubmitEnroll');

        const captureCountEl = document.getElementById('captureCount');
        const cameraStatus = document.getElementById('cameraStatus');
        const doFaceEnroll = document.getElementById('doFaceEnroll');

        const enrollAlert = document.getElementById('enrollAlert');

        let stream = null;
        let photos = [];

        function showAlert(type, message) {
            enrollAlert.className = `alert alert-${type}`;
            enrollAlert.textContent = message;
            enrollAlert.classList.remove('d-none');
        }
        function hideAlert() {
            enrollAlert.classList.add('d-none');
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }
            video.srcObject = null;
            cameraStatus.innerText = 'Đã tắt camera';
            updateUI();
        }

        function updateUI() {
            captureCountEl.innerText = `${photos.length}/${MAX_PHOTOS} ảnh`;

            btnCapture.disabled = !stream || photos.length >= MAX_PHOTOS;
            btnClear.disabled = photos.length === 0;

            btnStop.disabled = !stream;

            btnSubmit.disabled = photos.length !== MAX_PHOTOS;

            doFaceEnroll.value = photos.length > 0 ? "1" : "0";
        }

        function renderThumbsAndInputs() {
            thumbs.innerHTML = '';
            inputsWrap.innerHTML = '';

            photos.forEach((dataUrl, idx) => {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = `
                <div class="position-relative">
                    <img src="${dataUrl}" style="width:100%; height:90px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                        onclick="window.__removeFacePhoto(${idx})">x</button>
                </div>
            `;
                thumbs.appendChild(col);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'face_photos[]';
                input.value = dataUrl;
                inputsWrap.appendChild(input);
            });

            updateUI();
        }

        window.__removeFacePhoto = function(index) {
            photos.splice(index, 1);
            renderThumbsAndInputs();
        }

        btnStart.addEventListener('click', async () => {
            hideAlert();
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "user" },
                    audio: false
                });
                video.srcObject = stream;
                cameraStatus.innerText = 'Camera đã bật';
                updateUI();
            } catch (e) {
                console.error(e);
                cameraStatus.innerText = 'Không bật được camera (check quyền trình duyệt)';
                showAlert('danger', 'Không bật được camera. Hãy cấp quyền camera cho trình duyệt hoặc chạy bằng https/localhost.');
            }
        });

        btnStop.addEventListener('click', () => stopCamera());

        btnCapture.addEventListener('click', () => {
            hideAlert();
            if (!stream) return;
            if (photos.length >= MAX_PHOTOS) return;

            const w = video.videoWidth;
            const h = video.videoHeight;

            canvas.width = w;
            canvas.height = h;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, w, h);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
            photos.push(dataUrl);

            renderThumbsAndInputs();
        });

        btnClear.addEventListener('click', () => {
            photos = [];
            renderThumbsAndInputs();
            hideAlert();
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideAlert();

            if (photos.length !== MAX_PHOTOS) {
                showAlert('warning', `Cần đủ ${MAX_PHOTOS} ảnh để đăng ký.`);
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Đang gửi...';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form),
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data?.message || 'Đăng ký thất bại');
                }

                showAlert('success', data.message || 'Đăng ký khuôn mặt thành công!');
                stopCamera();

            } catch (err) {
                console.error(err);
                showAlert('danger', err.message || 'Có lỗi xảy ra khi đăng ký.');
            } finally {
                btnSubmit.innerText = 'Gửi đăng ký (10 ảnh)';
                updateUI();
            }
        });

        window.addEventListener('beforeunload', () => stopCamera());

        updateUI();
    </script>
@endsection
