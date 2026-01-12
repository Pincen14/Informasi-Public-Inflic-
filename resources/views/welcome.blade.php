<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome</title>

    <!-- Fonts (opsional, aman walau tanpa CSS) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f5;">

    <!-- Top Right Menu -->
    <div style="position:absolute; top:20px; right:20px;">
        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            |
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>

    <!-- Main Content -->
    <div style="max-width:720px; text-align:center; padding:24px; background:#ffffff; border-radius:8px;">
        <h1 style="font-size:32px; font-weight:bold;">
            Sistem Informasi Lost & Found
        </h1>

        <p style="margin-top:16px; color:#555;">
            Platform pelaporan dan verifikasi barang hilang dan ditemukan.
            Pengguna dapat melaporkan barang, sedangkan admin bertugas melakukan
            verifikasi dan klaim.
        </p>

        <div style="margin-top:32px; display:flex; justify-content:center; gap:16px;">
            <a href="{{ route('login') }}"
               style="padding:16px 24px; border:1px solid #ddd; border-radius:6px; text-decoration:none;">
                <strong>Login</strong><br>
                <small>Masuk ke akun</small>
            </a>

            <a href="{{ route('register') }}"
               style="padding:16px 24px; border:1px solid #ddd; border-radius:6px; text-decoration:none;">
                <strong>Register</strong><br>
                <small>Buat akun baru</small>
            </a>
        </div>

        <div style="margin-top:40px; font-size:12px; color:#999;">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            (PHP v{{ PHP_VERSION }})
        </div>
    </div>

</div>
</body>
</html>
