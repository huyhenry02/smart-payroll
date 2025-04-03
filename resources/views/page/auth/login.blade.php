<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống Agribank</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, #f8f2f2, #fdfdfd);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-wrapper {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            width: 400px;
        }

        .agribank-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .agribank-logo img {
            width: 240px;
            height: 32px;
            object-fit: contain;
        }

        h3 {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #4b1c1c;
        }

        label {
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
            display: block;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #b22222;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #b22222;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #991a1a;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #b22222;
            text-decoration: none;
            font-size: 13px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="agribank-logo">
        <img src="/assets/img/logo.svg" alt="Agribank Logo">
    </div>
    <h3>Đăng nhập hệ thống nội bộ</h3>

    <form action="{{ route('auth.postLogin') }}" method="post">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Nhập email của bạn" name="email" required>

        <label for="password">Mật khẩu</label>
        <input type="password" id="password" placeholder="Nhập mật khẩu" name="password" required>

        <button type="submit">Tiếp tục</button>
    </form>
</div>
</body>
</html>
