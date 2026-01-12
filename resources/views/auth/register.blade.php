<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Infogritas</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .slide { transition: opacity 1.2s ease-in-out; }

        .primary-color { background-color: #1a0f91; }
        .primary-hover:hover { background-color: #150c70; }
        .text-primary { color: #1a0f91; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp .8s ease forwards; }
    </style>
</head>

<body class="bg-white text-sm">
<div class="flex h-screen overflow-hidden">

    <!-- LEFT : CAROUSEL -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900">
        <div class="absolute inset-0 bg-black/50 z-10"></div>

        <div id="slide-0" class="slide absolute inset-0 opacity-100">
            <img src="{{ asset('images/login-register1.jpeg') }}"
              class="w-full h-full object-cover">
        </div>
        <div id="slide-1" class="slide absolute inset-0 opacity-0">
           <img src="{{ asset('images/login-register2.jpeg') }}"
                 class="w-full h-full object-cover">
        </div>
        <div id="slide-2" class="slide absolute inset-0 opacity-0">
            <img src="{{ asset('images/login-register3.jpeg') }}"
                 class="w-full h-full object-cover">
        </div>

        <div class="relative z-20 p-12 flex flex-col justify-end text-white fade-up">
            <h2 id="slide-title" class="text-2xl font-semibold mb-3">
                Temukan barang yang dicari
            </h2>
            <p id="slide-description" class="text-sm text-white/80 max-w-md mb-6">
                Kami hadir membantu menemukan barang kesayangan Anda
            </p>

            <div class="flex gap-2">
                <button onclick="goToSlide(0)" id="indicator-0" class="h-1.5 w-10 rounded-full bg-white"></button>
                <button onclick="goToSlide(1)" id="indicator-1" class="h-1.5 w-2 rounded-full bg-white/40"></button>
                <button onclick="goToSlide(2)" id="indicator-2" class="h-1.5 w-2 rounded-full bg-white/40"></button>
            </div>
        </div>
    </div>

    <!-- RIGHT : REGISTER FORM -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8 overflow-y-auto">
        <div class="w-full max-w-sm py-8 fade-up">

            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-semibold text-primary mb-2 tracking-tight">
                    Welcome to Infogritas
                </h1>
                <p class="text-gray-500 text-sm">
                    Daftarkan akunmu untuk melanjutkan
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <ul class="text-xs text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs text-gray-600 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs text-gray-600 mb-1">No Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs text-gray-600 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <input type="hidden" id="password_confirmation" name="password_confirmation">

                <div>
                    <label class="block text-xs text-gray-600 mb-1">Role</label>
                    <select name="role" required
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500 outline-none text-gray-600">
                        <option value="">Pilih Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-md text-white font-medium transition transform hover:-translate-y-0.5 primary-color primary-hover">
                    Register
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">
                    Login
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    let currentSlide = 0;
    const slides = [
        { title: "Temukan barang yang dicari", description: "Kami hadir membantu menemukan barang kesayangan Anda" },
        { title: "Hadir sebagai solusi", description: "Solusi modern untuk barang hilang dan ditemukan" },
        { title: "Selalu bawa barang kesayanganmu", description: "Jangan sampai kehilangan barang berhargamu" }
    ];

    function goToSlide(index) {
        for (let i = 0; i < 3; i++) {
            document.getElementById(`slide-${i}`).classList.replace('opacity-100','opacity-0');
            document.getElementById(`indicator-${i}`).className = "h-1.5 w-2 rounded-full bg-white/40";
        }

        document.getElementById(`slide-${index}`).classList.replace('opacity-0','opacity-100');
        document.getElementById(`indicator-${index}`).className = "h-1.5 w-10 rounded-full bg-white";

        document.getElementById('slide-title').textContent = slides[index].title;
        document.getElementById('slide-description').textContent = slides[index].description;

        currentSlide = index;
    }

    setInterval(() => {
        currentSlide = (currentSlide + 1) % 3;
        goToSlide(currentSlide);
    }, 5000);

    document.getElementById('password').addEventListener('input', function () {
        document.getElementById('password_confirmation').value = this.value;
    });
</script>

</body>
</html>
