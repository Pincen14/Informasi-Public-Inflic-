@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Detail Barang Ditemukan</h1>
                <p style="opacity: 0.9;">Informasi lengkap barang yang hilang</p>
            </div>
            <a href="{{ route('dashboard.user') }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-medium">
                ← Kembali
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Foto & Info Barang -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Foto Barang -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($item->image && \Storage::disk('public')->exists($item->image))
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama_item }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">Tidak ada foto</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Informasi Barang -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Informasi Barang</h2>

                <div class="space-y-4">
                    <!-- Nama Barang -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Barang</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $item->nama_item }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-900">{{ $item->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Lokasi Ditemukan</label>
                        <p class="text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $item->location_found }}
                        </p>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Ditemukan</label>
                            <p class="text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->date_found)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Waktu Ditemukan</label>
                            <p class="text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->time_found)->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Ditemukan Oleh -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ditemukan Oleh</label>
                        <p class="text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $item->finder_name }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Kontak & Aksi -->
        <div class="space-y-6">

            <!-- Kontak Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Hubungi Kami</h2>

                @if($item->admin_contact)
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500 mb-2 block">Untuk Mengklaim Barang Ini:</label>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <p class="text-lg font-bold text-purple-900 break-all">
                            {{ $item->admin_contact }}
                        </p>
                        <p class="text-xs text-purple-600 mt-2">
                            📞 WhatsApp / Email
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <!-- WhatsApp Button (jika nomornya) -->
                    <!-- @if(preg_match('/^[\d\s\+\-\(\)]+$/', $item->admin_contact))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->admin_contact) }}" 
                               target="_blank"
                               class="block w-full bg-green-600 text-white text-center py-3 rounded-lg hover:bg-green-700 transition font-medium">
                                💬 Chat via WhatsApp
                            </a>
                        @endif  -->

                    <!-- Email Button (jika emailnya) -->
                    @if(filter_var($item->admin_contact, FILTER_VALIDATE_EMAIL))
                    <a href="mailto:{{ $item->admin_contact }}"
                        class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                        ✉️ Kirim Email
                    </a>
                    @endif

                    <!-- Button Klaim -->
                    <a href="{{ route('items.claim.form', $item->id) }}"
                        class="block w-full bg-purple-700 text-white text-center py-3 rounded-lg hover:bg-purple-800 transition font-medium">
                        📋 Ajukan Klaim Barang
                    </a>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        ⚠️ Kontak belum tersedia. Admin sedang memproses laporan ini.
                    </p>
                </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Cara Mengklaim:</p>
                        <ol class="list-decimal list-inside space-y-1 text-blue-700">
                            <li>Hubungi kontak yang tertera</li>
                            <li>Atau ajukan klaim melalui form</li>
                            <li>Siapkan bukti kepemilikan</li>
                            <li>Admin akan menghubungi Anda</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Share (Optional) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Bagikan ke Teman</h3>
                <div class="flex gap-2">
                    <button onclick="shareWhatsApp()" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition text-sm">
                        WhatsApp
                    </button>
                    <button onclick="copyLink()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                        Copy Link
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript -->
<script>
    function shareWhatsApp() {
        const text = "Barang ditemukan: {{ $item->nama_item }} di {{ $item->location_found }}";
        const url = window.location.href;
        window.open(`https://wa.me/?text=${encodeURIComponent(text + ' - ' + url)}`, '_blank');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link berhasil dicopy!');
        });
    }
</script>

@endsection