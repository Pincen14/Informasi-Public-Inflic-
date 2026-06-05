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
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
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
                                @else
                                <a href="{{ route('dashboard.user') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                                <a href="{{ route('items.create') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Lapor Barang</a>
                                @endif
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile.edit') : route('profile.edit') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-base font-medium">Profile</a>
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

        <!-- Toast Notifications -->
        <div x-data="{ 
                show: true, 
                message: '{{ session('success') ?? session('error') ?? '' }}', 
                type: '{{ session('success') ? 'success' : (session('error') ? 'error' : '') }}'
             }" 
             x-init="if(message) { setTimeout(() => show = false, 4000) }"
             x-show="show && message"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-[9999] max-w-sm w-full bg-white shadow-xl rounded-xl border border-gray-100 pointer-events-auto overflow-hidden"
             style="display: none;">
            <div class="p-4">
                <div class="flex items-start">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <template x-if="type === 'success'">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </template>
                        <template x-if="type === 'error'">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </template>
                    </div>
                    <!-- Message -->
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-semibold text-gray-900" x-text="type === 'success' ? 'Sukses' : 'Gagal'"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="message"></p>
                    </div>
                    <!-- Close Button -->
                    <div class="ml-4 flex-shrink-0 flex flex-items-start">
                        <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="h-1 bg-gradient-to-r" :class="type === 'success' ? 'from-green-400 to-green-600' : 'from-red-400 to-red-600'" style="animation: toastProgress 4s linear forwards;"></div>
        </div>

        <style>
            @keyframes toastProgress {
                from { width: 100%; }
                to { width: 0%; }
            }
        </style>

        <!-- Page Content -->
        <main>
            @yield('content')
            {{ $slot ?? '' }}
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