@extends('layouts.app')

@section('title', 'Ajukan Klaim Barang')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Ajukan Klaim Barang</h1>
                <p style="opacity: 0.9;">Isi formulir di bawah untuk mengklaim barang Anda</p>
            </div>
            <a href="{{ route('items.show', $item->id) }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition font-medium">
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
                <p class="font-medium mb-1">Informasi Penting:</p>
                <ul class="list-disc list-inside space-y-1 text-blue-700">
                    <li>Klaim akan ditinjau oleh admin</li>
                    <li>Siapkan KTP/Identitas untuk verifikasi</li>
                    <li>Admin akan menghubungi Anda melalui kontak yang tercantum</li>
                    <li>Pastikan informasi yang Anda berikan akurat</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">

        <!-- Informasi Barang yang Diklaim -->
        <div class="mb-6 pb-6 border-b">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Barang yang Diklaim</h2>
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
                    <h3 class="text-xl font-bold text-gray-900">{{ $item->nama_item }}</h3>
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

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p class="font-bold mb-2">Ada Error:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('items.claim', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informasi Pengambil -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Pengambil</h2>

                <!-- Nama Pengambil -->
                <div class="mb-4">
                    <label for="nama_pengambil" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama_pengambil"
                        id="nama_pengambil"
                        value="{{ old('nama_pengambil', auth()->user()->name ?? '') }}"
                        placeholder="Nama lengkap Anda"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nama_pengambil') border-red-500 @enderror"
                        required>
                    @error('nama_pengambil')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor KTP -->
                <div class="mb-4">
                    <label for="nomor_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor NIM atau KTP <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="NIMorKTP"
                        id="NIMorKTP"
                        value="{{ old('NIMorKTP') }}"
                        placeholder="Contoh: 3201234567890001"
                        maxlength="16"
                        pattern="[0-9]{16}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nomor_ktp') border-red-500 @enderror"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Masukkan nomor KTP atau NIM</p>
                    @error('NIMorKTP')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Pengambil -->
                <div class="mb-4">
                    <label for="phone_pengambil" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="phone_pengambil"
                        id="phone_pengambil"
                        value="{{ old('phone_pengambil', auth()->user()->phone ?? '') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('phone_pengambil') border-red-500 @enderror"
                        required>
                    <p class="mt-2 text-sm text-gray-500">
                        💡 Admin akan menghubungi Anda melalui nomor ini
                    </p>
                    @error('phone_pengambil')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Pengambil -->
                <div class="mb-4">
                    <label for="foto_pengambil" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Diri/Selfie <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="foto_pengambil" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                    <span>Upload foto selfie</span>
                                    <input
                                        id="foto_pengambil"
                                        name="foto_pengambil"
                                        type="file"
                                        accept="image/*"
                                        class="sr-only"
                                        onchange="previewSelfie(event)"
                                        required>
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                        </div>
                    </div>

                    <!-- Preview Image -->
                    <div id="selfiePreview" class="mt-4 hidden">
                        <img id="previewSelfie" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg mx-auto">
                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        📸 Upload foto selfie Anda untuk identifikasi saat pengambilan barang
                    </p>

                    @error('foto_pengambil')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Image -->
                <div id="ktpPreview" class="mt-4 hidden">
                    <img id="previewKTP" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg mx-auto border-2 border-purple-200">
                </div>

                @error('foto_pengambil')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Ambil -->
            <div class="mb-4">
                <label for="tgl_ambil" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Pengambilan <span class="text-red-500">*</span>
                </label>
                <input
                    type="date"
                    name="tgl_ambil"
                    id="tgl_ambil"
                    value="{{ old('tgl_ambil', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('tgl_ambil') border-red-500 @enderror"
                    required>
                <p class="mt-2 text-sm text-gray-500">
                    📅 Kapan Anda ingin mengambil barang ini?
                </p>
                @error('tgl_ambil')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
    </div>

    <!-- Persetujuan -->
    <div class="mb-6">
        <label class="flex items-start">
            <input
                type="checkbox"
                name="agreement"
                class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                required>
            <span class="text-sm text-gray-700">
                Saya menyatakan bahwa barang ini adalah <strong>milik saya</strong> dan informasi yang saya berikan adalah <strong>benar dan akurat</strong>.
                Saya bersedia menunjukkan KTP asli saat pengambilan barang.
            </span>
        </label>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button
            type="submit"
            class="flex-1 bg-purple-700 text-white px-6 py-3 rounded-lg hover:bg-purple-800 transition font-medium">
            📋 Kirim Klaim
        </button>
        <a
            href="{{ route('items.show', $item->id) }}"
            class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
            Batal
        </a>
    </div>

    </form>

</div>

<!-- Warning Box -->
<div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
    <div class="flex">
        <svg class="h-5 w-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div class="text-sm text-red-800">
            <p class="font-medium mb-1">⚠️ Peringatan:</p>
            <p>Memberikan informasi palsu atau mencoba mengklaim barang yang bukan milik Anda dapat berakibat pada tindakan hukum.</p>
        </div>
    </div>
</div>

</div>

<!-- JavaScript untuk Preview KTP -->
<script>
    function previewKTP(event) {
        const preview = document.getElementById('previewKTP');
        const previewContainer = document.getElementById('ktpPreview');
        const file = event.target.files[0];

        if (file) {
            // Validasi file type
            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar (JPG, PNG, atau JPEG)');
                event.target.value = '';
                return;
            }

            // Validasi file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                previewContainer.style.display = 'block';
            }
            reader.onerror = function() {
                alert('Gagal membaca file. Coba lagi.');
            }
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection