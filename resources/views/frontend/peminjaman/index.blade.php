@extends('frontend.layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Peminjaman Saya</h1>
            <p class="mt-2 text-gray-600">Kelola dan pantau status peminjaman Anda</p>
        </div>
        <div class="flex space-x-3">
            <!-- Tombol Ajukan Peminjaman -->
            <a href="{{ route('frontend.peminjaman.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajukan Peminjaman Baru
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('frontend.peminjaman.index') }}" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('frontend.peminjaman.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Peminjaman List -->
    @if($peminjamans->count() > 0)
        <div class="space-y-6">
            @foreach($peminjamans as $peminjaman)
                <div class="bg-white shadow rounded-lg overflow-hidden transition-all hover:shadow-md">
                    <div class="px-6 py-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex-1">
                                <!-- Header -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $peminjaman->kode_peminjaman }}
                                    </h3>
                                    <span class="mt-2 md:mt-0 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if(($peminjaman->status == 'disetujui' || $peminjaman->status == 'dipinjam') && $peminjaman->tanggal_kembali_rencana < now() && !$peminjaman->tanggal_kembali_aktual)
                                            bg-red-100 text-red-800
                                        @else
                                            {{ $peminjaman->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            ($peminjaman->status == 'disetujui' ? 'bg-blue-100 text-blue-800' :
                                            ($peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' :
                                            ($peminjaman->status == 'dipinjam' ? 'bg-green-100 text-green-800' :
                                            ($peminjaman->status == 'dikembalikan' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-800')))) }}
                                        @endif">
                                        {{ ucfirst($peminjaman->status) }}
                                        @if(($peminjaman->status == 'disetujui' || $peminjaman->status == 'dipinjam') && $peminjaman->tanggal_kembali_rencana < now() && !$peminjaman->tanggal_kembali_aktual)
                                            - <span class="font-bold">Segera kembalikan!</span>
                                        @endif
                                    </span>
                                </div>

                                <!-- Content -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Barang Info -->
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <h4 class="font-medium text-gray-900">{{ $peminjaman->barang->nama }}</h4>
                                        <p class="text-sm text-gray-600">{{ $peminjaman->barang->kode_barang }}</p>
                                        <p class="text-sm text-gray-600">Kategori: {{ $peminjaman->barang->kategori->nama }}</p>
                                        <p class="text-sm text-gray-600">Jumlah: {{ $peminjaman->jumlah ?? 1 }} unit</p>
                                    </div>

                                    <!-- Tanggal Info -->
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Tanggal Pinjam:</span> 
                                            {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Rencana Kembali:</span> 
                                            {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                                        </p>
                                        @if($peminjaman->tanggal_kembali_aktual)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Kembali Aktual:</span> 
                                                {{ $peminjaman->tanggal_kembali_aktual->format('d/m/Y') }}
                                            </p>
                                        @endif
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Durasi:</span> 
                                            {{ $peminjaman->durasi ?? $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali_rencana) }} hari
                                        </p>
                                    </div>
                                </div>

                                <!-- Keperluan -->
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Keperluan:</span> 
                                        {{ $peminjaman->keperluan }}
                                    </p>
                                </div>

                                <!-- Catatan Admin -->
                                @if($peminjaman->catatan_admin)
                                    <div class="mt-4 p-3 bg-blue-50 rounded-md">
                                        <p class="text-sm text-blue-700">
                                            <span class="font-medium">Catatan Admin:</span> 
                                            {{ $peminjaman->catatan_admin }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Warning untuk terlambat -->
                                @if(($peminjaman->status == 'disetujui' || $peminjaman->status == 'dipinjam') && $peminjaman->tanggal_kembali_rencana < now() && !$peminjaman->tanggal_kembali_aktual)
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                                        <p class="text-sm text-red-700 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <span class="font-medium">Terlambat {{ abs(floor(now()->diffInDays($peminjaman->tanggal_kembali_rencana))) }} hari! Segera kembalikan barang.</span> 
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 md:mt-0 md:ml-6 flex md:flex-col space-x-4 md:space-x-0 md:space-y-2">
                                <a href="{{ route('frontend.peminjaman.show', $peminjaman) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                                <a href="{{ route('frontend.barang.show', $peminjaman->barang) }}" 
                                   class="text-gray-600 hover:text-gray-800 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Lihat Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $peminjamans->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white shadow rounded-lg text-center py-12 px-4">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada peminjaman</h3>
            <p class="mt-2 text-sm text-gray-500">Mulai dengan mengajukan peminjaman barang pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.peminjaman.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Add Alpine.js for dropdown -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection