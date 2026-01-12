@extends('layouts.app')

@section('title', 'Detail Laporan - Admin')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Detail Laporan</h1>
                <p style="opacity: 0.9;">Informasi lengkap barang yang ditemukan</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-medium">
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
                @if($item->image && file_exists(public_path('items/' . $item->image)))
                @php
                $imagePath = public_path('items/' . $item->image);
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageMime = mime_content_type($imagePath);
                $imageSrc = 'data:' . $imageMime . ';base64,' . $imageData;
                @endphp
                <img src="{{ $imageSrc }}" alt="{{ $item->nama_item }}" class="w-full h-96 object-cover">
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

                    <!-- Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <div class="mt-1">
                            @if($item->status == 'pending')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Verifikasi
                            </span>
                            @elseif($item->status == 'approved')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Disetujui
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                Sudah Diambil
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Info Penemu & Aksi -->
        <div class="space-y-6">

            <!-- Informasi Penemu (PRIVATE - HANYA ADMIN) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4 pb-2 border-b">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Informasi Penemu</h2>
                </div>
                <p class="text-xs text-red-600 mb-4 bg-red-50 p-2 rounded">
                    ⚠️ Info ini PRIVATE - Hanya untuk admin
                </p>

                <div class="space-y-3">
                    <!-- Nama Penemu -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Penemu</label>
                        <p class="text-gray-900 font-semibold">{{ $item->finder_name }}</p>
                    </div>

                    <!-- Kontak Penemu (PRIVATE) -->
                    <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                        <label class="text-sm font-medium text-gray-500">Kontak Penemu</label>
                        <p class="text-gray-900 font-semibold break-all">{{ $item->finder_contact }}</p>
                        <p class="text-xs text-gray-500 mt-1">WhatsApp / Email</p>
                    </div>

                    <!-- User yang Submit -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Dilaporkan oleh</label>
                        <p class="text-gray-900">{{ $item->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $item->user->email ?? '-' }}</p>
                    </div>

                    <!-- Tanggal Laporan -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Laporan</label>
                        <p class="text-gray-900 text-sm">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Kontak Admin (PUBLIC) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Kontak Admin (Publik)</h2>

                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-500">Kontak yang Ditampilkan ke User</label>
                    @if($item->admin_contact)
                    <p class="text-gray-900 font-semibold">{{ $item->admin_contact }}</p>
                    @else
                    <p class="text-gray-400 italic">Belum diisi</p>
                    @endif
                </div>

                <a href="{{ route('admin.items.edit', $item->id) }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Edit Kontak Admin
                </a>
            </div>

            <!-- Aksi Admin -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b">Aksi</h2>

                <div class="space-y-3">
                    @if($item->status == 'pending')
                    <!-- Approve -->
                    <form action="{{ route('admin.items.approve', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-medium" onclick="return confirm('Setujui laporan ini?')">
                            ✓ Setujui Laporan
                        </button>
                    </form>
                    @endif

                    @if($item->status == 'approved')
                    <!-- Mark as Taken -->
                    <form action="{{ route('admin.items.taken', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium" onclick="return confirm('Tandai barang sudah diambil?')">
                            ✓ Tandai Sudah Diambil
                        </button>
                    </form>
                    @endif

                    <!-- Reject/Delete -->
                    <form action="{{ route('admin.items.reject', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-medium" onclick="return confirm('Hapus laporan ini? Tidak bisa dikembalikan!')">
                            🗑 Hapus Laporan
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection