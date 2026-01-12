@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="relative">
    <img
        src="https://images.unsplash.com/photo-1505691938895-1758d7feb511"
        class="w-full h-[420px] object-cover"
        alt="Hero Image"
    />
    <div class="absolute inset-0 bg-black/40 flex items-center">
        <h1 class="text-white text-6xl font-bold ml-20 tracking-wide">
            Inflic
        </h1>
    </div>
</section>

<!-- MAIN CONTENT -->
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

    <!-- SIDEBAR -->
    <aside class="bg-white rounded-xl p-6 shadow">
        <h3 class="font-semibold mb-4">Kategori</h3>
        <ul class="space-y-3 text-sm">
            <li>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="rounded">
                    <span>Semua Informasi</span>
                </label>
            </li>
            <li>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="rounded">
                    <span>Sedang Hilang</span>
                </label>
            </li>
            <li>
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="rounded">
                    <span>Sudah Ditemukan</span>
                </label>
            </li>
        </ul>
    </aside>

    <!-- ITEM GRID -->
    <section class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($items as $item)
            <div class="bg-white rounded-2xl p-4 shadow hover:shadow-lg transition">

                <!-- IMAGE -->
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    class="w-full h-48 object-cover rounded-xl mb-3"
                >

                <!-- TAG -->
                <div class="flex gap-2 mb-2">
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        {{ $item->status ?? 'Hilang' }}
                    </span>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        {{ $item->location ?? 'Tidak diketahui' }}
                    </span>
                </div>

                <!-- TITLE -->
                <h4 class="font-semibold">
                    {{ $item->name }}
                </h4>

                <!-- DESCRIPTION -->
                <p class="text-sm text-gray-500 mt-1">
                    {{ Str::limit($item->description, 80) }}
                </p>

                <!-- ACTION -->
                <div class="mt-4">
                    <a
                        href="{{ route('items.show', $item->id) }}"
                        class="block text-center bg-black text-white rounded-full py-2 text-sm hover:bg-gray-800 transition"
                    >
                        Hubungi Admin
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-3">
                Belum ada barang yang tersedia.
            </p>
        @endforelse

    </section>

</main>

<!-- CTA -->
<section class="bg-gradient-to-r from-gray-900 to-gray-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8 items-center">

        <div>
            <h2 class="text-3xl font-bold mb-2">
                Menemukan Barang?
            </h2>
            <p class="text-gray-300">
                Laporkan agar pemiliknya dapat segera menemukannya kembali.
            </p>
        </div>

        <div class="md:text-right">
            <a
                href="{{ route('items.create') }}"
                class="inline-block bg-white text-black px-6 py-2 rounded-full font-semibold hover:bg-gray-200 transition"
            >
                Upload Barang
            </a>
        </div>

    </div>
</section>

@endsection
