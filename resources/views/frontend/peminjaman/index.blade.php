@extends('frontend.layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Peminjaman Saya</h1>
            <p class="mt-2 text-gray-600">Kelola dan pantau status peminjaman Anda</p>
        </div>
        <div class="flex space-x-3">
            <!-- Export Dropdown -->
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <div>
                    <button @click="open = !open" type="button" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Data
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                    <div class="py-1">
                        <a href="{{ route('frontend.peminjaman.export', 'xlsx') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            Export ke Excel
                        </a>
                        <a href="{{ route('frontend.peminjaman.export', 'pdf') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            Export ke PDF
                        </a>
                        <a href="{{ route('frontend.peminjaman.export', 'csv') }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                            Export ke CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tombol Ajukan Peminjaman -->
            <a href="{{ route('frontend.peminjaman.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                Ajukan Peminjaman Baru
            </a>
        </div>
    </div>


    <!-- Filter -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('frontend.peminjaman.index') }}" class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('frontend.peminjaman.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Peminjaman List -->
    @if($peminjamans->count() > 0)
        <div class="space-y-6">
            @foreach($peminjamans as $peminjaman)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $peminjaman->kode_peminjaman }}
                                    </h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $peminjaman->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($peminjaman->status == 'disetujui' ? 'bg-blue-100 text-blue-800' :
                                           ($peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' :
                                           ($peminjaman->status == 'dipinjam' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ $peminjaman->label_status }}
                                    </span>
                                </div>

                                <!-- Content -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Barang Info -->
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $peminjaman->barang->nama }}</h4>
                                        <p class="text-sm text-gray-600">{{ $peminjaman->barang->kode_barang }}</p>
                                        <p class="text-sm text-gray-600">Kategori: {{ $peminjaman->barang->kategori->nama }}</p>
                                        <p class="text-sm text-gray-600">Jumlah: {{ $peminjaman->jumlah }} unit</p>
                                    </div>

                                    <!-- Tanggal Info -->
                                    <div>
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
                                            {{ $peminjaman->durasi }} hari
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
                                    <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Catatan Admin:</span> 
                                            {{ $peminjaman->catatan_admin }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Warning untuk terlambat -->
                                @if($peminjaman->status == 'dipinjam' && $peminjaman->terlambat)
                                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                                        <p class="text-sm text-red-700">
                                            ⚠️ <span class="font-medium">Terlambat {{ $peminjaman->hari_terlambat }} hari!</span> 
                                            Segera kembalikan barang.
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="ml-6 flex flex-col space-y-2">
                                <a href="{{ route('frontend.peminjaman.show', $peminjaman) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('frontend.barang.show', $peminjaman->barang) }}" 
                                   class="text-gray-600 hover:text-gray-800 text-sm">
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
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada peminjaman</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan mengajukan peminjaman barang pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('frontend.peminjaman.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Ajukan Peminjaman
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Add Alpine.js for dropdown -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection