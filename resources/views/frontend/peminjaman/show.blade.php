@extends('frontend.layouts.app')

@section('title', 'Detail Peminjaman - ' . $peminjaman->kode_peminjaman)

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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
                    <a href="{{ route('frontend.peminjaman.index') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">
                        Peminjaman Saya
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $peminjaman->kode_peminjaman }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Peminjaman</h1>
                <p class="mt-2 text-gray-600">{{ $peminjaman->kode_peminjaman }}</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                {{ $peminjaman->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                   ($peminjaman->status == 'disetujui' ? 'bg-blue-100 text-blue-800' :
                   ($peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' :
                   ($peminjaman->status == 'dipinjam' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))) }}">
                {{ $peminjaman->label_status }}
            </span>
        </div>
    </div>

    <!-- Warning untuk terlambat -->
    @if($peminjaman->status == 'dipinjam' && $peminjaman->terlambat)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Peminjaman Terlambat!</h3>
                    <p class="mt-1 text-sm text-red-700">
                        Anda terlambat {{ $peminjaman->hari_terlambat }} hari dari tanggal yang dijadwalkan. Segera kembalikan barang atau hubungi admin.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Barang -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Barang</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center space-x-4">
                        @if($peminjaman->barang->foto)
                            <img src="{{ Storage::url($peminjaman->barang->foto) }}" alt="{{ $peminjaman->barang->nama }}" 
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-gray-900">{{ $peminjaman->barang->nama }}</h4>
                            <p class="text-gray-600">{{ $peminjaman->barang->kode_barang }}</p>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $peminjaman->barang->kategori->nama }}
                                </span>
                                <span class="text-sm text-gray-600">📍 {{ $peminjaman->barang->lokasi }}</span>
                            </div>
                        </div>
                        <a href="{{ route('frontend.barang.show', $peminjaman->barang) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            Lihat Detail Barang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Peminjaman -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Peminjaman</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->jumlah }} unit</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->durasi }} hari</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Rencana Kembali</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</dd>
                        </div>

                        @if($peminjaman->tanggal_kembali_aktual)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kembali Aktual</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_kembali_aktual->format('d/m/Y') }}</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Keperluan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->keperluan }}</dd>
                        </div>

                        @if($peminjaman->catatan_admin)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="bg-gray-50 rounded-md p-3">
                                    {{ $peminjaman->catatan_admin }}
                                </div>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6" style="height: 350px;">
            <!-- Status Timeline -->
            <div class="bg-white shadow rounded-lg h-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Status Timeline</h3>
                </div>
                <div class="px-6 py-4 flex ">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Submitted -->
                            <li>
                                <div class="relative pb-8">
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <p class="text-sm text-gray-500">
                                                Peminjaman diajukan <span class="font-medium text-gray-900">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Pending/Approved/Rejected -->
                            <li>
                                <div class="relative pb-8">
                                    @if($peminjaman->status !== 'pending')
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                {{ $peminjaman->status == 'pending' ? 'bg-yellow-500' : 
                                                   ($peminjaman->status == 'disetujui' || $peminjaman->status == 'dipinjam' || $peminjaman->status == 'dikembalikan' ? 'bg-green-500' : 'bg-red-500') }}">
                                                @if($peminjaman->status == 'pending')
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif($peminjaman->status == 'ditolak')
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <p class="text-sm text-gray-500">
                                                @if($peminjaman->status == 'pending')
                                                    Menunggu persetujuan admin
                                                @elseif($peminjaman->status == 'disetujui')
                                                    Peminjaman disetujui
                                                @elseif($peminjaman->status == 'ditolak')
                                                    Peminjaman ditolak
                                                @elseif($peminjaman->status == 'dipinjam')
                                                    Barang sedang dipinjam
                                                @elseif($peminjaman->status == 'dikembalikan')
                                                    Barang telah dikembalikan
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            @if(in_array($peminjaman->status, ['dipinjam', 'dikembalikan']))
                            <!-- Borrowed -->
                            <li>
                                <div class="relative pb-8">
                                    @if($peminjaman->status == 'dikembalikan')
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <p class="text-sm text-gray-500">
                                                Barang dipinjamkan pada <span class="font-medium text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            @if($peminjaman->status == 'dikembalikan')
                            <!-- Returned -->
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <p class="text-sm text-gray-500">
                                                Barang dikembalikan pada <span class="font-medium text-gray-900">{{ $peminjaman->tanggal_kembali_aktual->format('d/m/Y') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi</h3>
                    <div class="space-y-3">
                        <a href="{{ route('frontend.peminjaman.index') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Kembali ke Daftar
                        </a>
                        
                        @if($peminjaman->status == 'pending')
                        <p class="text-sm text-gray-500 text-center">
                            Anda dapat menghubungi admin jika ada pertanyaan tentang status peminjaman.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection