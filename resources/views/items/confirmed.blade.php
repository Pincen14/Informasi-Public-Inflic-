@extends('layouts.app')

@section('title', 'Barang Dikonfirmasi - Inflic')

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-600 to-teal-700 text-white py-12 mb-8 shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 mb-2 border border-green-300">
                    ✓ Selesai Dikonfirmasi
                </span>
                <h1 class="text-3xl md:text-4xl font-bold">Detail Penyerahan Barang</h1>
                <p class="text-green-100 mt-1">Laporan barang ini telah diselesaikan dan dikembalikan.</p>
            </div>
            <a href="{{ route('dashboard.user') }}" class="bg-white text-green-700 px-5 py-2.5 rounded-xl hover:bg-green-50 transition font-medium flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Item Photo and Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Item Photo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($item->image && \Storage::disk('public')->exists($item->image))
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama_item }}" class="w-full h-[400px] object-cover">
                @else
                    <div class="w-full h-[400px] bg-gray-50 flex items-center justify-center border-b border-gray-100">
                        <div class="text-center">
                            <svg class="w-20 h-20 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">Tidak ada foto barang</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Item Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Informasi Barang Temuan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Barang</label>
                            <p class="text-lg font-bold text-gray-900 mt-0.5">{{ $item->nama_item }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi Penemuan</label>
                            <p class="text-gray-700 mt-0.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $item->location_found }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu & Tanggal Ditemukan</label>
                            <p class="text-gray-700 mt-0.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->date_found)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->time_found)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ditemukan Oleh</label>
                            <p class="text-gray-700 mt-0.5">{{ $item->finder_name }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Deskripsi Tambahan</label>
                            <p class="text-gray-700 mt-0.5 break-words">{{ $item->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Handover and Contact Details -->
        <div class="space-y-8">
            <!-- Handover Confirmation Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Penyerahan
                </h2>

                @if($item->claim)
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Pengambil</label>
                            <p class="text-base font-semibold text-gray-800">{{ $item->claim->nama_pengambil }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Pengambilan</label>
                            <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->claim->tgl_ambil)->format('d M Y') }}</p>
                        </div>

                        <!-- Documentation Image -->
                        @if($item->claim->foto_pengambil)
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-2">Foto Bukti Penyerahan</label>
                                <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                    <img src="{{ Str::startsWith($item->claim->foto_pengambil, 'claims') ? asset('storage/' . $item->claim->foto_pengambil) : asset('claims/' . $item->claim->foto_pengambil) }}" 
                                         alt="Bukti Penyerahan" 
                                         class="w-full h-48 object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                        <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm font-semibold text-green-950">Selesai via Admin</p>
                        <p class="text-xs text-green-700 mt-1">Barang telah diserahkan kembali kepada pemiliknya yang sah langsung melalui verifikasi Admin.</p>
                    </div>
                @endif
            </div>

            <!-- Admin Contact Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 00.96.725h1.582a1 1 0 00.96-.725l.548-2.2A1 1 0 0115.3 3H18a2 2 0 012 2v3.28a1 1 0 01-.725.94l-2.2.548a1 1 0 00-.725.96v1.582a1 1 0 00.725.96l2.2.548a1 1 0 01.725.94V18a2 2 0 01-2 2h-3.28a1 1 0 01-.94-.725l-.548-2.2a1 1 0 00-.96-.725h-1.582a1 1 0 00-.96.725l-.548 2.2a1 1 0 01-.94.725H5a2 2 0 01-2-2v-3.28a1 1 0 01.725-.94l2.2-.548a1 1 0 00.725-.96v-1.582a1 1 0 00-.725-.96l-2.2-.548A1 1 0 013 8.3V5z"></path>
                    </svg>
                    Hubungi Admin
                </h2>
                
                @if($item->admin_contact)
                    <p class="text-xs text-gray-500 mb-3">Ada pertanyaan atau sanggahan terkait barang ini? Hubungi admin resmi kami:</p>
                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-3.5 text-center mb-4">
                        <p class="text-sm font-mono font-bold text-purple-900 break-all">{{ $item->admin_contact }}</p>
                    </div>

                    @if(filter_var($item->admin_contact, FILTER_VALIDATE_EMAIL))
                        <a href="mailto:{{ $item->admin_contact }}" class="block w-full text-center bg-purple-700 text-white text-sm font-semibold py-3 rounded-xl hover:bg-purple-800 transition shadow-sm">
                            Kirimi Email Admin
                        </a>
                    @endif
                @else
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500">Kontak admin tidak tersedia untuk item ini. Silakan kunjungi pusat informasi admin terdekat.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
