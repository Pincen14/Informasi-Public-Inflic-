@extends('layouts.app')

@section('title', 'Laporkan Barang Ditemukan')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Laporkan Barang yang Ditemukan</h1>
        <p class="opacity-90">Bantu orang lain menemukan barang kesayangan mereka</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    
    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-bold mb-2">Ada Error:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informasi Barang -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Informasi Barang</h2>
                
                <!-- Nama Barang -->
                <div class="mb-4">
                    <label for="nama_item" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama_item" 
                        id="nama_item" 
                        value="{{ old('nama_item') }}"
                        placeholder="Contoh: Dompet Hitam, HP Samsung, Kacamata" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nama_item') border-red-500 @enderror"
                        required
                    >
                    @error('nama_item')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Barang
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        placeholder="Jelaskan ciri-ciri barang, warna, merek, kondisi, dll..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Foto -->
                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Barang <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-500 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none">
                                    <span>Upload foto</span>
                                    <input 
                                        id="image" 
                                        name="image" 
                                        type="file" 
                                        accept="image/*"
                                        class="sr-only" 
                                        onchange="previewImage(event)"
                                        required
                                    >
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                        </div>
                    </div>
                    
                    <!-- Preview Image -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="preview" src="" alt="Preview" class="max-w-full h-64 object-cover rounded-lg mx-auto">
                    </div>
                    
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Lokasi & Waktu -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Lokasi & Waktu Penemuan</h2>
                
                <!-- Lokasi Ditemukan -->
                <div class="mb-4">
                    <label for="location_found" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Ditemukan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="location_found" 
                        id="location_found" 
                        value="{{ old('location_found') }}"
                        placeholder="Contoh: Gedung A Lantai 2, Kantin, Parkiran" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('location_found') border-red-500 @enderror"
                        required
                    >
                    @error('location_found')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal & Waktu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label for="date_found" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Ditemukan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="date_found" 
                            id="date_found" 
                            value="{{ old('date_found', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('date_found') border-red-500 @enderror"
                            required
                        >
                        @error('date_found')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu -->
                    <div>
                        <label for="time_found" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Ditemukan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="time" 
                            name="time_found" 
                            id="time_found" 
                            value="{{ old('time_found') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('time_found') border-red-500 @enderror"
                            required
                        >
                        @error('time_found')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Penemu -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Informasi Penemu</h2>
                
                <!-- Nama Penemu -->
                <div class="mb-4">
                    <label for="finder_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="finder_name" 
                        id="finder_name" 
                        value="{{ old('finder_name', auth()->user()->name ?? '') }}"
                        placeholder="Nama lengkap kamu" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('finder_name') border-red-500 @enderror"
                        required
                    >
                    @error('finder_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kontak Penemu -->
                <div class="mb-4">
                    <label for="finder_contact" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor WhatsApp / Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="finder_contact" 
                        id="finder_contact" 
                        value="{{ old('finder_contact') }}"
                        placeholder="Contoh: 08123456789 atau email@example.com" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('finder_contact') border-red-500 @enderror"
                        required
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Kontak ini hanya akan dilihat oleh admin, tidak ditampilkan ke publik
                    </p>
                    @error('finder_contact')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button 
                    type="submit" 
                    class="flex-1 bg-purple-700 text-white px-6 py-3 rounded-lg hover:bg-purple-800 transition font-medium"
                >
                    Kirim Laporan
                </button>
                <a 
                    href="{{ route('dashboard.user') }}" 
                    class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium"
                >
                    Batal
                </a>
            </div>

        </form>

    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <p class="font-medium mb-1">Catatan Penting:</p>
                <ul class="list-disc list-inside space-y-1 text-blue-700">
                    <li>Laporan akan ditinjau oleh admin sebelum ditampilkan di dashboard</li>
                    <li>Pastikan informasi yang kamu berikan akurat dan jelas</li>
                    <li>Kontak kamu hanya akan digunakan oleh admin untuk keperluan koordinasi</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<!-- JavaScript untuk Preview Image -->
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection