<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%),
            url('{{ asset("images/welcome-page.png") }}') center/cover no-repeat;
            min-height: 100vh;
            color: #fff;
            overflow: hidden;
        }


        .auth {
            position: absolute;
            top: 24px;
            right: 32px;
            z-index: 10;
        }

        .auth a {
            color: #fff;
            text-decoration: none;
            margin-left: 16px;
            font-weight: 500;
            opacity: .85;
            transition: opacity .3s;
        }

        .auth a:hover {
            opacity: 1;
        }

        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .content {
            max-width: 640px;
            text-align: center;
            animation: fadeUp 1s ease forwards;
        }

        h1 {
            font-size: 36px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        p {
            font-size: 16px;
            opacity: .9;
            margin-bottom: 36px;
        }

        .buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 36px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            background: #3b1fa6;
            color: white;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- AUTH MENU -->
    <div class="auth">
        @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>

    <!-- MAIN -->
    <div class="container">
        <div class="content">
            <h1>
                Temukan Barang Kesayangan,<br>
                Jangan Sampai Kehilangan
            </h1>

            <p>
                Platform pencarian modern untuk melaporkan dan menemukan kembali
                barang berharga secara cepat dan aman.
            </p>

            <div class="buttons">
                <a href="{{ route('register') }}" class="btn">Daftar</a>
                <a href="{{ route('login') }}" class="btn">Masuk</a>
            </div>
        </div>
    </div>

</body>

</html>