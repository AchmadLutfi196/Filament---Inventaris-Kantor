@extends('frontend.layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ajukan Peminjaman</h1>
        <p class="mt-2 text-gray-600">Isi formulir berikut untuk mengajukan peminjaman barang</p>
    </div>

    <form method="POST" action="{{ route('frontend.peminjaman.store') }}" class="space-y-6" id="peminjaman-form">
        @csrf

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada formulir:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pilih Barang -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pilih Barang</h3>
            </div>
            <div class="px-6 py-4">
                @if($barang)
                    <!-- Barang sudah dipilih -->
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}" id="barang_id">
                    <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center space-x-4">
                            @if($barang->foto)
                                <img src="{{ Storage::url($barang->foto) }}" alt="{{ $barang->nama }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $barang->nama }}</h4>
                                <p class="text-sm text-gray-600">{{ $barang->kode_barang }}</p>
                                <p class="text-sm text-gray-600">Stok tersedia: {{ $barang->stok_tersedia }} unit</p>
                                @if($barang->harga_sewa_per_hari && $barang->biaya_deposit)
                                <div class="mt-2 flex space-x-4">
                                    <span class="text-sm font-medium text-green-600">{{ $barang->formatted_harga_sewa }}/hari</span>
                                    <span class="text-sm font-medium text-blue-600">Deposit: {{ $barang->formatted_deposit }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Belum memilih barang -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0118 28c2.624 0 4.991 1.013 6.713 2.686"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada barang dipilih</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Anda harus memilih barang yang akan dipinjam terlebih dahulu sebelum melanjutkan.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('frontend.barang.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="mr-2 -ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Lihat Daftar Barang
                            </a>
                        </div>
                        <input type="hidden" name="barang_id" id="barang_id" value="{{ old('barang_id') }}">
                        @error('barang_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </div>

        @if($barang)
        <!-- Detail Peminjaman -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Peminjaman</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" 
                           min="1" max="{{ $barang->stok_tersedia }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">
                        Maksimal {{ $barang->stok_tersedia }} unit
                    </p>
                    @error('jumlah')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    {{-- @error('tanggal_pinjam')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror --}}
                </div>

                <!-- Tanggal Kembali -->
                <div>
                    <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    {{-- @error('tanggal_kembali_rencana')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror --}}
                </div>

                <!-- Keperluan -->
                <div>
                    <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan</label>
                    <textarea name="keperluan" id="keperluan" rows="3" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Jelaskan keperluan peminjaman...">{{ old('keperluan') }}</textarea>
                    {{-- @error('keperluan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror --}}
                </div>
            </div>
        </div>

        <!-- Ringkasan Biaya -->
        <div class="bg-white shadow rounded-lg" id="ringkasan-biaya" style="display: none;">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Ringkasan Biaya</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Hari:</span>
                        <span class="text-sm font-medium" id="display-jumlah-hari">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Biaya Sewa:</span>
                        <span class="text-sm font-medium" id="display-biaya-sewa">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Deposit:</span>
                        <span class="text-sm font-medium" id="display-biaya-deposit">-</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between">
                        <span class="text-base font-semibold text-gray-900">Total Pembayaran:</span>
                        <span class="text-base font-bold text-blue-600" id="display-total-biaya">-</span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 rounded-md">
                    <p class="text-sm text-yellow-800">
                        <strong>Catatan:</strong> Deposit akan dikembalikan setelah barang dikembalikan dalam kondisi baik.
                        Denda keterlambatan 5% per hari dari total biaya sewa.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-button" disabled>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"></path>
                    </svg>
                    Ajukan Peminjaman & Bayar
                </button>
            </div>
        </div>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('peminjaman-form');
    const barangId = document.getElementById('barang_id');
    const jumlah = document.getElementById('jumlah');
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const ringkasanBiaya = document.getElementById('ringkasan-biaya');
    const submitButton = document.getElementById('submit-button');

    console.log('Script loaded');

    // Update minimum tanggal kembali saat tanggal pinjam berubah
    if (tanggalPinjam) {
        tanggalPinjam.addEventListener('change', function() {
            if (tanggalKembali) {
                tanggalKembali.min = this.value;
                updateRingkasanBiaya();
            }
        });
    }

    // Event listeners untuk update ringkasan biaya
    [jumlah, tanggalPinjam, tanggalKembali].forEach(element => {
        if (element) {
            element.addEventListener('change', updateRingkasanBiaya);
            element.addEventListener('input', updateRingkasanBiaya);
        }
    });

    function updateRingkasanBiaya() {
        console.log('updateRingkasanBiaya called');
        
        if (!barangId || !barangId.value || !jumlah || !tanggalPinjam || !tanggalKembali) {
            console.log('Missing elements');
            return;
        }

        if (!jumlah.value || !tanggalPinjam.value || !tanggalKembali.value) {
            console.log('Missing values');
            ringkasanBiaya.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        console.log('Fetching barang details...');
        
        // Simplified route - just use the path
        const routeUrl = '/frontend/peminjaman/get-barang-details';
        
        console.log('Using route URL:', routeUrl);
        
        // Fetch detail barang dan hitung biaya
        fetch(routeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                barang_id: barangId.value,
                jumlah: jumlah.value,
                tanggal_pinjam: tanggalPinjam.value,
                tanggal_kembali: tanggalKembali.value
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.error) {
                console.log('Error in response:', data.error);
                alert('Error: ' + data.error);
                ringkasanBiaya.style.display = 'none';
                submitButton.disabled = true;
                return;
            }

            // Update display
            document.getElementById('display-jumlah-hari').textContent = data.perhitungan.jumlah_hari + ' hari';
            document.getElementById('display-biaya-sewa').textContent = data.perhitungan.formatted_biaya_sewa;
            document.getElementById('display-biaya-deposit').textContent = data.perhitungan.formatted_biaya_deposit;
            document.getElementById('display-total-biaya').textContent = data.perhitungan.formatted_total;

            ringkasanBiaya.style.display = 'block';
            submitButton.disabled = false;
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Network error: ' + error.message);
            ringkasanBiaya.style.display = 'none';
            submitButton.disabled = true;
        });
    }

    // Validasi form sebelum submit
    if (form) {
        form.addEventListener('submit', function(event) {
            console.log('Form submitted');
            
            if (jumlah) {
                const max = parseInt(jumlah.getAttribute('max') || 0);
                const value = parseInt(jumlah.value || 0);
                
                if (max > 0 && value > max) {
                    event.preventDefault();
                    alert(`Jumlah maksimal yang dapat dipinjam adalah ${max} unit`);
                    jumlah.value = max;
                    jumlah.focus();
                    return false;
                }
                
                if (value < 1) {
                    event.preventDefault();
                    alert('Jumlah minimal peminjaman adalah 1 unit');
                    jumlah.value = 1;
                    jumlah.focus();
                    return false;
                }
            }
        });
    }

    // Initial check
    updateRingkasanBiaya();
});
</script>
@endsection