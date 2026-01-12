@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<!-- Hero Section -->
<div style="background: linear-gradient(to right, #7c3aed, #5b21b6);" class="text-white py-12 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Dashboard Admin</h1>
        <p style="opacity: 0.9;">Kelola laporan barang hilang & ditemukan</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600">{{ $approvedCount }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Taken -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Sudah Diambil</p>
                    <p class="text-3xl font-bold text-gray-600">{{ $takenCount }}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <a href="{{ route('admin.dashboard') }}" class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ request('status') == '' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua ({{ $pendingCount + $approvedCount + $takenCount }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ request('status') == 'pending' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}" class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ request('status') == 'approved' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Approved ({{ $approvedCount }})
                </a>
                <a href="{{ route('admin.dashboard', ['status' => 'taken']) }}" class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ request('status') == 'taken' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Taken ({{ $takenCount }})
                </a>
            </nav>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penemu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50">
                        <!-- Barang -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">

                                <div class="h-12 w-12 flex-shrink-0">
                                    @if($item->image && file_exists(public_path('items/' . $item->image)))
                                    @php
                                    $imagePath = public_path('items/' . $item->image);
                                    $imageData = base64_encode(file_get_contents($imagePath));
                                    $imageMime = mime_content_type($imagePath);
                                    $imageSrc = 'data:' . $imageMime . ';base64,' . $imageData;
                                    @endphp
                                    <img class="h-12 w-12 rounded object-cover" src="{{ $imageSrc }}" alt="{{ $item->nama_item }}">
                                    @else
                                    <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_item }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 40) }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Penemu -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->finder_name }}</div>
                            <div class="text-sm text-gray-500">{{ $item->finder_contact }}</div>
                        </td>

                        <!-- Lokasi -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->location_found }}</div>
                        </td>

                        <!-- Tanggal -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($item->date_found)->format('d M Y') }}<br>
                            {{ \Carbon\Carbon::parse($item->time_found)->format('H:i') }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status == 'pending')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            @elseif($item->status == 'approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Approved
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Taken
                            </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <!-- Detail -->
                                <a href="{{ route('admin.items.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @if($item->status == 'pending')
                                <!-- Approve -->
                                <form action="{{ route('admin.items.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Approve" onclick="return confirm('Setujui laporan ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                @if($item->status == 'approved')
                                <!-- Mark as Taken -->
                                <form action="{{ route('admin.items.taken', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900" title="Tandai Sudah Diambil" onclick="return confirm('Tandai barang sudah diambil?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                <!-- Reject/Delete -->
                                <form action="{{ route('admin.items.reject', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Tolak/Hapus" onclick="return confirm('Hapus laporan ini? Tidak bisa dikembalikan!')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada laporan</h3>
            <p class="mt-1 text-sm text-gray-500">Belum ada laporan barang yang masuk.</p>
        </div>
        @endif
    </div>

</div>

@endsection