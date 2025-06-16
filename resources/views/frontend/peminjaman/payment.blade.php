@extends('frontend.layouts.app')

@section('title', 'Pembayaran Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pembayaran Peminjaman</h1>
        <p class="mt-2 text-gray-600">Kode Peminjaman: <span class="font-semibold">{{ $peminjaman->kode_peminjaman }}</span></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Detail Peminjaman -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Peminjaman</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <!-- Barang -->
                <div class="flex items-center space-x-4">
                    @if($peminjaman->barang->foto)
                        <img src="{{ Storage::url($peminjaman->barang->foto) }}" alt="{{ $peminjaman->barang->nama }}" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $peminjaman->barang->nama }}</h4>
                        <p class="text-sm text-gray-600">{{ $peminjaman->barang->kode_barang }}</p>
                    </div>
                </div>

                <!-- Informasi Peminjaman -->
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah:</span>
                        <span class="text-sm font-medium">{{ $peminjaman->jumlah }} unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tanggal Pinjam:</span>
                        <span class="text-sm font-medium">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tanggal Kembali:</span>
                        <span class="text-sm font-medium">{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Durasi:</span>
                        <span class="text-sm font-medium">{{ $peminjaman->jumlah_hari }} hari</span>
                    </div>
                    @if($peminjaman->keperluan)
                    <div class="pt-2">
                        <span class="text-sm text-gray-600">Keperluan:</span>
                        <p class="text-sm font-medium mt-1">{{ $peminjaman->keperluan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ringkasan Pembayaran</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Biaya Sewa ({{ $peminjaman->jumlah_hari }} hari × {{ $peminjaman->jumlah }} unit):</span>
                        <span class="text-sm font-medium">{{ $peminjaman->formatted_total_biaya_sewa }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Deposit ({{ $peminjaman->jumlah }} unit):</span>
                        <span class="text-sm font-medium">{{ $peminjaman->formatted_total_deposit }}</span>
                    </div>
                    @if($peminjaman->denda_keterlambatan > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-red-600">Denda Keterlambatan:</span>
                        <span class="text-sm font-medium text-red-600">{{ $peminjaman->formatted_denda_keterlambatan }}</span>
                    </div>
                    @endif
                    <hr class="my-3">
                    <div class="flex justify-between">
                        <span class="text-base font-semibold text-gray-900">Total Pembayaran:</span>
                        <span class="text-lg font-bold text-blue-600">{{ $peminjaman->formatted_total_pembayaran }}</span>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="mt-6">
                    @if($peminjaman->payment_status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Menunggu Pembayaran</h3>
                                    <p class="mt-1 text-sm text-yellow-700">Silakan klik tombol "Bayar Sekarang" untuk melanjutkan pembayaran.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($peminjaman->payment_status === 'paid')
                        <div class="bg-green-50 border border-green-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Pembayaran Berhasil</h3>
                                    <p class="mt-1 text-sm text-green-700">Pembayaran telah berhasil diproses pada {{ $peminjaman->paid_at?->format('d/m/Y H:i') }}.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Pembayaran Gagal</h3>
                                    <p class="mt-1 text-sm text-red-700">Terjadi kesalahan dalam pembayaran. Silakan coba lagi.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tombol Pembayaran -->
                @if($peminjaman->payment_status === 'pending')
                <div class="mt-6">
                    <button type="button" id="pay-button" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"></path>
                        </svg>
                        Bayar Sekarang
                    </button>
                </div>
                @endif

                <!-- Informasi Tambahan -->
                <div class="mt-6 p-3 bg-blue-50 rounded-md">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Deposit akan dikembalikan setelah barang dikembalikan dalam kondisi baik</li>
                        <li>• Denda keterlambatan 5% per hari dari total biaya sewa</li>
                        <li>• Pembayaran menggunakan sistem Midtrans yang aman</li>
                        <li>• Simpan kode peminjaman untuk referensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Midtrans Snap -->
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    
    if (payButton) {
        payButton.addEventListener('click', function () {
            // Disable button to prevent double click
            this.disabled = true;
            this.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...';
            
            // Trigger Midtrans Snap
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert('Pembayaran berhasil!');
                    window.location.href = '{{ route("frontend.peminjaman.show", $peminjaman->id) }}';
                },
                onPending: function(result) {
                    alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                    window.location.href = '{{ route("frontend.peminjaman.show", $peminjaman->id) }}';
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                    // Re-enable button
                    payButton.disabled = false;
                    payButton.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"></path></svg>Bayar Sekarang';
                },
                onClose: function() {
                    // Re-enable button when popup is closed
                    payButton.disabled = false;
                    payButton.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"></path></svg>Bayar Sekarang';
                }
            });
        });
    }
});
</script>
@endsection