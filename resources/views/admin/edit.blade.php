@extends('layouts.app')

@section('title', 'Edit Kontak Admin')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Edit Kontak Admin</h1>
                <p style="opacity: 0.9;">Kontak yang akan ditampilkan ke pengguna</p>
            </div>
            <a href="{{ route('admin.items.show', $item->id) }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-medium">
                ← Kembali
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <!-- Info Box -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Catatan Penting:</p>
                <ul class="list-disc list-inside space-y-1 text-blue-700">
                    <li>Kontak ini akan ditampilkan kepada user di halaman detail barang</li>
                    <li>User akan menghubungi kontak ini untuk mengklaim barang</li>
                    <li>Kontak penemu ({{ $item->finder_contact }}) tetap PRIVATE dan tidak ditampilkan</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">

        <!-- Informasi Barang -->
        <div class="mb-6 pb-6 border-b">
            <div class="flex items-start">
                <div class="h-20 w-20 flex-shrink-0 mr-4">
                    @if($item->image && file_exists(public_path('items/' . $item->image)))
                    @php
                    $imagePath = public_path('items/' . $item->image);
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $imageMime = mime_content_type($imagePath);
                    $imageSrc = 'data:' . $imageMime . ';base64,' . $imageData;
                    @endphp
                    <img src="{{ $imageSrc }}" alt="{{ $item->nama_item }}" class="h-20 w-20 rounded object-cover">
                    @else
                    <div class="h-20 w-20 rounded bg-gray-200 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $item->nama_item }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $item->location_found }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kontak Admin -->
            <div class="mb-6">
                <label for="admin_contact" class="block text-sm font-medium text-gray-700 mb-2">
                    Kontak Admin (Publik) <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="admin_contact"
                    id="admin_contact"
                    value="{{ old('admin_contact', $item->admin_contact) }}"
                    placeholder="Contoh: 08123456789 (WhatsApp) atau email@admin.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('admin_contact') border-red-500 @enderror"
                    required>
                <p class="mt-2 text-sm text-gray-500">
                    💡 Tips: Gunakan nomor WhatsApp kantor atau email official, bukan kontak pribadi penemu.
                </p>
                @error('admin_contact')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informasi Private (Read Only) -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    🔒 Informasi Private (Hanya untuk Admin)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Nama Penemu:</p>
                        <p class="font-medium text-gray-900">{{ $item->finder_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kontak Penemu:</p>
                        <p class="font-medium text-gray-900">{{ $item->finder_contact }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    ⚠️ Info ini TIDAK akan ditampilkan ke user. Hanya admin yang bisa melihat.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    class="flex-1 bg-purple-700 text-white px-6 py-3 rounded-lg hover:bg-purple-800 transition font-medium">
                    💾 Simpan Perubahan
                </button>
                <a
                    href="{{ route('admin.items.show', $item->id) }}"
                    class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                    Batal
                </a>
            </div>

        </form>

    </div>

    <!-- Preview Kontak -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">👁️ Preview (Yang Dilihat User)</h3>
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <p class="text-sm text-gray-600 mb-2">Kontak untuk Klaim Barang:</p>
            <p class="text-lg font-semibold text-gray-900" id="preview-contact">
                {{ $item->admin_contact ?? 'Belum diisi' }}
            </p>
        </div>
    </div>

</div>

<!-- JavaScript untuk Live Preview -->
<script>
    document.getElementById('admin_contact').addEventListener('input', function(e) {
        const previewElement = document.getElementById('preview-contact');
        previewElement.textContent = e.target.value || 'Belum diisi';
    });
</script>

@endsection