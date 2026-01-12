<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- SLOT CONTENT --}}
    <div class="py-12">


        <div class="max-w-7xl mx-auto px-6 mb-6">
            <h1 class="text-3xl font-bold text-red-600">
                ISI DASHBOARD + HOMEPAGE
            </h1>
        </div>

        <!-- Hero -->
        <section class="relative">
            <img
                src="https://images.unsplash.com/photo-1505691938895-1758d7feb511"
                class="w-full h-[420px] object-cover"
            />
            <div class="absolute inset-0 bg-black/30 flex items-center">
                <h1 class="text-white text-6xl font-bold ml-20">Shop</h1>
            </div>
        </section>

        <!-- Main -->
        <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Sidebar -->
            <aside class="bg-white rounded-xl p-6 shadow">
                <h3 class="font-semibold mb-4">Category</h3>
                <ul class="space-y-2 text-sm">
                    <li><input type="checkbox"> All Product</li>
                    <li><input type="checkbox"> For Home</li>
                    <li><input type="checkbox"> For Music</li>
                    <li><input type="checkbox"> For Office</li>
                </ul>
            </aside>

            <!-- Product Grid -->
            <section class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products ?? range(1,6) as $product)
                    <div class="bg-white rounded-2xl p-4 shadow">
                        <img src="https://via.placeholder.com/300" class="rounded-xl mb-4">
                        <h4 class="font-semibold">Product</h4>
                        <p class="font-bold">$29.90</p>
                    </div>
                @endforeach
            </section>

        </main>
    </div>
</x-app-layout>



{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

