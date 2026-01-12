@extends('layouts.app')

@section('title', 'Dashboard - Barang Hilang')

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-16 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Temukan Barang Kesayangan,</h1>
        <h1 class="text-4xl md:text-5xl font-bold">Jangan Sampai Kehilangan</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Title -->
    <!-- <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Barang hilang</h2>
    </div> -->

    <!-- Filter & Search Bar -->
    <div class="mb-6 flex flex-col md:flex-row gap-4 items-center">
        <!-- Filter Buttons -->
        <div class="flex gap-2">
            <button class="px-6 py-2 rounded-full text-white bg-purple-700 hover:bg-purple-800 transition font-medium">
                Semua
            </button>
            <button class="px-6 py-2 rounded-full text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition font-medium">
                Belum Ditemukan
            </button>
            <button class="px-6 py-2 rounded-full text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition font-medium">
                Sudah Ditemukan
            </button>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 w-full md:w-auto">
            <form action="{{ route('dashboard.user') }}" method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Barang Kamu Sekarang" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>

        <!-- Button Laporkan -->
        <a href="{{ route('items.create') }}" class="px-6 py-2 rounded-lg text-white bg-purple-700 hover:bg-purple-800 transition font-medium whitespace-nowrap">
            Cari
        </a>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
            @foreach($items as $item)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition">
                    <!-- Image -->
                    <div class="h-48 bg-gray-100 overflow-hidden relative">
                        @if($item->image)
                            <img src="{{ asset('items/' . $item->image) }}" alt="{{ $item->nama_item }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <!-- Title -->
                        <h3 class="text-base font-bold text-gray-900 mb-2">{{ $item->nama_item }}</h3>
                        
                        <!-- Description -->
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ Str::limit($item->description ?? 'Tidak ada deskripsi', 60) }}
                        </p>

                        <!-- Location -->
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-1">
                                <span class="font-semibold">Lokasi:</span> {{ $item->location_found }}
                            </p>
                        </div>

                        <!-- Button Hubungi -->
                        <a href="{{ route('items.show', $item->id) }}" class="block w-full text-center bg-purple-700 text-white text-sm font-medium py-2 rounded-lg hover:bg-purple-800 transition">
                            Hubungi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $items->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada barang yang dilaporkan</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search'))
                    Tidak ditemukan hasil untuk "{{ request('search') }}"
                @else
                    Mulai laporkan barang yang kamu temukan
                @endif
            </p>
            <a href="{{ route('items.create') }}" class="inline-block px-6 py-3 bg-purple-700 text-white rounded-lg hover:bg-purple-800 transition font-medium">
                Laporkan Barang Sekarang
            </a>
        </div>
    @endif

</div>

<!-- Custom Pagination Style -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@endsection