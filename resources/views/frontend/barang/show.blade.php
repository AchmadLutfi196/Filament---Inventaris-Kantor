@extends('frontend.layouts.app')

@section('title', $barang->nama)

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('frontend.dashboard') }}" class="text-gray-700 hover:text-blue-600">
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('frontend.barang.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">
                        Daftar Barang
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $barang->nama }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
        <!-- Image Gallery -->
        <div class="flex flex-col-reverse">
            <div class="aspect-w-3 aspect-h-2 rounded-lg overflow-hidden">
                @if($barang->foto)
                    <img src="{{ Storage::url($barang->foto) }}" alt="{{ $barang->nama }}" class="w-full h-full object-center object-cover">
                @else
                    <div class="flex items-center justify-center h-64 bg-gray-200 text-gray-400">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
            <!-- Category -->
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ $barang->kategori->nama }}
                </span>
            </div>

            <!-- Title, Price, & Code -->
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $barang->nama }}</h1>  
            <span class="text-xl font-bold text-green-600">Rp {{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}</span>
            <p class="text-lg text-gray-500 mt-2">{{ $barang->kode_barang }}</p>

            <!-- Availability -->
            <div class="mt-6">
                <h3 class="sr-only">Ketersediaan</h3>
                <div class="flex items-center">
                    <div class="flex items-center">
                        @if($barang->stok_tersedia > 0)
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <p class="ml-2 text-sm text-gray-900">{{ $barang->stok_tersedia }} unit tersedia dari {{ $barang->stok }} total</p>
                        @else
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <p class="ml-2 text-sm text-gray-900">Stok habis</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900">Informasi Detail</h3>
                <div class="mt-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kondisi:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $barang->kondisi == 'baik' ? 'bg-green-100 text-green-800' : 
                               ($barang->kondisi == 'rusak ringan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($barang->kondisi) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Lokasi:</span>
                        <span class="text-sm text-gray-900">{{ $barang->lokasi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="text-sm text-gray-900">{{ $barang->tersedia ? 'Dapat dipinjam' : 'Tidak dapat dipinjam' }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($barang->deskripsi)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900">Deskripsi</h3>
                <div class="mt-4 text-sm text-gray-600">
                    <p>{{ $barang->deskripsi }}</p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-8">
                @auth
                    @if($barang->stok_tersedia > 0 && $barang->tersedia)
                        <a href="{{ route('frontend.peminjaman.create', ['barang_id' => $barang->id]) }}" 
                           class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ajukan Peminjaman
                        </a>
                    @else
                        <button disabled class="w-full bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-500 cursor-not-allowed">
                            Tidak Tersedia
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login untuk Meminjam
                    </a>
                @endauth
            </div>

            <!-- Current Borrowings -->
            @if($barang->peminjamansAktif->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900">Sedang Dipinjam</h3>
                <div class="mt-4 space-y-2">
                    @foreach($barang->peminjamansAktif as $peminjaman)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ $peminjaman->jumlah }} unit</span>
                                <span class="text-sm text-gray-600">
                                    Kembali: {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection