{{-- resources/views/admin/homepage-admin.blade.php --}}
@extends('layouts.app')

@section('content')

<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl font-bold">Admin Dashboard</h1>
        <p class="text-gray-300 mt-2">
            Kelola dan verifikasi barang hilang & ditemukan
        </p>
    </div>
</section>

<!-- Main -->
<main class="max-w-7xl mx-auto px-6 py-12">

    <!-- Summary Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-xl p-6 shadow">
            <h4 class="text-sm text-gray-500">Total Barang</h4>
            <p class="text-3xl font-bold mt-2">
                {{ $stats['total'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow">
            <h4 class="text-sm text-gray-500">Belum Diverifikasi</h4>
            <p class="text-3xl font-bold mt-2 text-yellow-600">
                {{ $stats['pending'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow">
            <h4 class="text-sm text-gray-500">Sudah Diverifikasi</h4>
            <p class="text-3xl font-bold mt-2 text-green-600">
                {{ $stats['approved'] ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow">
            <h4 class="text-sm text-gray-500">Sudah Diklaim</h4>
            <p class="text-3xl font-bold mt-2 text-blue-600">
                {{ $stats['claimed'] ?? 0 }}
            </p>
        </div>
    </section>

    <!-- Item Table -->
    <section class="bg-white rounded-xl shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Daftar Barang</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama Barang</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items ?? [] as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                {{ $item->name ?? 'Tumbler' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $item->location ?? 'Lobby / Kelas' }}
                            </td>

                            <td class="px-4 py-3">
                                @if (($item->status ?? 'pending') === 'approved')
                                    <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">
                                        Approved
                                    </span>
                                @elseif (($item->status ?? 'pending') === 'claimed')
                                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">
                                        Claimed
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($item->created_at)->format('d M Y') ?? now()->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">

                                    <!-- Approve -->
                                    <form method="POST" action="{{ route('items.approve', $item->id ?? 0) }}">
                                        @csrf
                                        <button
                                            class="px-3 py-1 text-xs rounded bg-green-600 text-white hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Claim -->
                                    <form method="POST" action="{{ route('items.claim', $item->id ?? 0) }}">
                                        @csrf
                                        <button
                                            class="px-3 py-1 text-xs rounded bg-blue-600 text-white hover:bg-blue-700">
                                            Claim
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</main>

@endsection
