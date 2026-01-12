<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">

        <!-- Navbar -->
        <nav class="bg-white shadow-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-purple-700">
                            Inflic </a>
                    </div>

                    <!-- Menu (Desktop) -->
                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                        @if(auth()->user()->role === 'admin')
                        <!-- Admin Menu -->
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-purple-700 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard Admin
                        </a>
                        <a href="{{ route('admin.claims.index') }}" class="text-gray-700 hover:text-purple-700 px-3 py-2 rounded-md text-sm font-medium">
                            Klaim Masuk
                        </a>
                        @else
                        <!-- User Menu -->
                        <a href="{{ route('dashboard.user') }}" class="text-gray-700 hover:text-purple-700 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('items.create') }}" class="bg-purple-700 text-white hover:bg-purple-800 px-4 py-2 rounded-md text-sm font-medium">
                            Lapor Barang
                        </a>
                        @endif

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-purple-700 focus:outline-none">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-purple-700 text-white hover:bg-purple-800 px-4 py-2 rounded-md text-sm font-medium">Register</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center" x-data="{ mobileMenu: false }">
                        <button @click="mobileMenu = !mobileMenu" class="text-gray-700 hover:text-purple-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Mobile Menu Dropdown -->
                        <div x-show="mobileMenu" @click.away="mobileMenu = false" x-cloak class="absolute top-16 right-0 w-full bg-white border-t border-gray-200 shadow-lg z-50">
                            <div class="px-2 pt-2 pb-3 space-y-1">
                                @auth
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Dashboard Admin</a>
                                <a href="{{ route('admin.claims.index') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Klaim Masuk</a>
                                @else
                                <a href="{{ route('dashboard.user') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                                <a href="{{ route('items.create') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Lapor Barang</a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Logout</button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Login</a>
                                <a href="{{ route('register') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Register</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Inflic System. Kami hadir sebagai solusi.
                </p>
            </div>
        </footer>
    </div>

    <!-- Alpine.js Cloak Style -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>