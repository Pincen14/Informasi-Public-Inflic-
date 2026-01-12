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

        @forelse ($items ?? range(1,6) as $item)
            <div class="bg-white rounded-2xl p-4 shadow hover:shadow-lg transition">

                <img
                    src="https://simplesentimental.com/cdn/shop/products/IMG_2561_jpg_2000x.jpg?v=1680206421"
                    class="rounded-xl mb-4 w-full h-48 object-cover"
                    alt="Item Image"
                >

                <div class="flex gap-2 mb-2">
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        Hilang
                    </span>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        Kelas 2.04
                    </span>
                </div>

                <h4 class="font-semibold">
                    {{ is_array($item) ? ($item['name'] ?? 'Nama Barang') : 'Nama Barang' }}
                </h4>

                <p class="text-sm text-gray-500 mt-1">
                    Ditemukan tumbler seperti gambar di atas, dapat diambil di satpam lobby.
                </p>

                <div class="mt-4">
                    <button
                        class="w-full bg-black text-white rounded-full py-2 text-sm hover:bg-gray-800 transition"
                    >
                        Hubungi Admin
                    </button>
                </div>
            </div>
        @empty
            <p class="text-gray-500">
                Belum ada barang yang tersedia.
            </p>
        @endforelse

    </section>

</main>

<!-- RECOMMENDATION -->
<section class="max-w-7xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-semibold mb-6">
        Cari Barang Lainnya
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @for ($i = 0; $i < 3; $i++)
            <div class="bg-white rounded-2xl p-4 shadow">

                <img
                    src="https://www.mahada.co.id/wp-content/uploads/2021/06/Tumbler-Custom-Berbahan-Plastik-Dengan-Model-Insert-Paper-Warna-Hitam-4.jpg"
                    class="rounded-xl mb-4 w-full h-48 object-cover"
                    alt="Recommendation Image"
                >

                <div class="flex gap-2 mb-2">
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        Hilang
                    </span>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                        Perpustakaan
                    </span>
                </div>

                <h4 class="font-semibold">
                    Nama Barang
                </h4>

                <p class="text-sm text-gray-500 mt-1">
                    Barang ditemukan dan dapat diambil melalui admin terkait.
                </p>

                <div class="mt-4">
                    <button
                        class="w-full bg-black text-white rounded-full py-2 text-sm hover:bg-gray-800 transition"
                    >
                        Hubungi Admin
                    </button>
                </div>
            </div>
        @endfor
    </div>
</section>

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
                href="{{ route('items.index') }}"
                class="inline-block bg-white text-black px-6 py-2 rounded-full font-semibold hover:bg-gray-200 transition"
            >
                Upload Barang
            </a>
        </div>

    </div>
</section>

@endsection
