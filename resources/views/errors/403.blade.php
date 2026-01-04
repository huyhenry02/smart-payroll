<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền truy cập</title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial; background:#0b1220; color:#e5e7eb; margin:0;}
        .wrap{min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;}
        .card{max-width:720px; width:100%; background:#111a2e; border:1px solid rgba(255,255,255,.08);
            border-radius:16px; padding:28px; box-shadow:0 20px 60px rgba(0,0,0,.35);}
        .code{font-size:72px; font-weight:800; letter-spacing:2px; margin:0; line-height:1;}
        .title{font-size:22px; font-weight:700; margin:10px 0 6px;}
        .desc{opacity:.85; margin:0 0 18px; line-height:1.6;}
        .btns{display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;}
        a.btn{display:inline-flex; align-items:center; justify-content:center; padding:10px 14px;
            border-radius:10px; text-decoration:none; font-weight:600;}
        .primary{background:#2563eb; color:#fff;}
        .ghost{border:1px solid rgba(255,255,255,.16); color:#e5e7eb;}
        .hint{margin-top:16px; font-size:13px; opacity:.75;}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <p class="code">403</p>
        <div class="title">Không có quyền truy cập</div>
        <p class="desc">
            Tài khoản của bạn không được cấp quyền để truy cập chức năng này.
            Nếu bạn nghĩ đây là nhầm lẫn, hãy liên hệ quản trị để được phân quyền.
        </p>

        <div class="btns">
            <a class="btn primary" href="{{ url()->previous() }}">Quay lại</a>
            <a class="btn ghost" href="{{ route('auth.logout') }}">Đăng xuất</a>
        </div>

        <div class="hint">
            Route: <b>{{ optional(request()->route())->getName() }}</b>
        </div>
    </div>
</div>
</body>
</html>
