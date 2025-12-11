<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrasi TU FTI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #ff7b00, #ff5e62);
        }

        .login-card {
            background: #fff;
            width: 350px;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card img {
            width: 150px;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        h3 {
            font-size: 14px;
            font-weight: 500;
            margin: 5px 0 25px 0;
            color: #555;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #444;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #ff5e00;
        }

        .btn-login {
            background: #ff5e00;
            color: #fff;
            border: none;
            padding: 10px 0;
            width: 100%;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #e65100;
        }

        .forgot-password {
            display: block;
            margin-top: 12px;
            font-size: 13px;
            text-decoration: none;
            color: #555;
        }

        .forgot-password:hover {
            color: #ff5e00;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <img src="{{ asset('logo.jpeg') }}" alt="Logo Universitas YARSI">

        <h2>ADMINISTRASI TATA USAHA</h2>
        <h3>Fakultas Teknologi Informasi</h3>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>

            <a href="https://www.yarsi.ac.id/ganti-password-akun-yarsi" class="forgot-password">Ganti Password?</a>
        </form>
    </div>

</body>
</html>
