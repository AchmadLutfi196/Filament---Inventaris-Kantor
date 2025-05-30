@extends('frontend.layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ajukan Peminjaman</h1>
        <p class="mt-2 text-gray-600">Isi formulir berikut untuk mengajukan peminjaman barang</p>
    </div>

    <form method="POST" action="{{ route('frontend.peminjaman.store') }}" class="space-y-6">
        @csrf

        <!-- Pilih Barang -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pilih Barang</h3>
            </div>
            <div class="px-6 py-4">
                @if($barang)
                    <!-- Barang sudah dipilih -->
                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                    <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center space-x-4">
                            @if($barang->foto)
                                <img src="{{ Storage::url($barang->foto) }}" alt="{{ $barang->nama }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $barang->nama }}</h4>
                                <p class="text-sm text-gray-600">{{ $barang->kode_barang }} - {{ $barang->kategori->nama }}</p>
                                <p class="text-sm text-gray-600">Lokasi: {{ $barang->lokasi }}</p>
                                <p class="text-sm font-medium text-green-600">Stok Tersedia: {{ $barang->stok_tersedia }} unit</p>
                            </div>
                            <a href="{{ route('frontend.barang.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Ganti Barang
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Pilih Barang  -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Barang Belum Dipilih</h3>
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
                        <input type="hidden" name="barang_id" id="selected_barang_id" value="{{ old('barang_id') }}">
                        @error('barang_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Peminjaman -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Peminjaman</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <!-- Jumlah -->
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah yang Dipinjam</label>
                    <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $barang ? $barang->stok_tersedia : '' }}" value="{{ old('jumlah', 1) }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('jumlah')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">
                        @if($barang)
                            Maksimal {{ $barang->stok_tersedia }} unit
                        @else
                            Sesuaikan dengan stok yang tersedia
                        @endif
                    </p>
                </div>

                <!-- Tanggal Pinjam -->
                <div>
                    <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_pinjam')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Kembali -->
                <div>
                    <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700">Tanggal Rencana Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" 
                           value="{{ old('tanggal_kembali_rencana', date('Y-m-d', strtotime('+7 days'))) }}" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_kembali_rencana')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1" id="durasi_info">Durasi: 7 hari</p>
                </div>

                <!-- Keperluan -->
                <div>
                    <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan/Tujuan Peminjaman</label>
                    <textarea name="keperluan" id="keperluan" rows="4" placeholder="Jelaskan untuk apa barang ini akan digunakan..." 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Maksimal 500 karakter</p>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-700">Saya setuju dengan syarat dan ketentuan</label>
                        <ul class="mt-2 text-gray-600 space-y-1">
                            <li>• Bertanggung jawab atas barang yang dipinjam</li>
                            <li>• Mengembalikan barang sesuai jadwal yang ditentukan</li>
                            <li>• Mengganti jika barang hilang atau rusak</li>
                            <li>• Menggunakan barang sesuai keperluan yang disebutkan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('frontend.barang.index') }}" 
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:ring-2 focus:ring-gray-500">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                Ajukan Peminjaman
            </button>
        </div>
    </form>
</div>

<script>
// Auto calculate duration
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Inisialisasi aplikasi peminjaman'); // Debug info
    
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const durasiInfo = document.getElementById('durasi_info');
    
    // Ambil elemen form
    const form = document.querySelector('form');
    
    // Validasi form saat submit
    if (form) {
        form.addEventListener('submit', function(event) {
            // Validasi jumlah
            const jumlahInput = document.getElementById('jumlah');
            if (jumlahInput) {
                const max = parseInt(jumlahInput.getAttribute('max') || 0);
                const value = parseInt(jumlahInput.value || 0);
                
                console.log('Validasi jumlah:', { max, value }); // Debug info
                
                // Validasi jumlah tidak melebihi stok
                if (max > 0 && value > max) {
                    event.preventDefault(); // Mencegah form terkirim
                    alert(`Jumlah maksimal yang dapat dipinjam adalah ${max} unit`);
                    jumlahInput.value = max;
                    jumlahInput.focus();
                    return false;
                }
                
                // Validasi jumlah minimal
                if (value < 1) {
                    event.preventDefault(); // Mencegah form terkirim
                    alert('Jumlah minimal peminjaman adalah 1 unit');
                    jumlahInput.value = 1;
                    jumlahInput.focus();
                    return false;
                }
            }
            
            // Jika validasi berhasil, disable tombol submit untuk mencegah double-submit
            if (this.checkValidity()) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                }
            }
        });
    }

    // Validasi input jumlah
    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        // Validasi saat nilai berubah (input)
        jumlahInput.addEventListener('input', function() {
            validateJumlah(this);
        });
        
        // Validasi saat keluar dari field (blur)
        jumlahInput.addEventListener('blur', function() {
            validateJumlah(this);
        });
        
        // Validasi saat nilai diubah (change)
        jumlahInput.addEventListener('change', function() {
            validateJumlah(this);
        });
        
        // Pastikan hanya angka yang diinput
        jumlahInput.addEventListener('keypress', function(e) {
            const charCode = e.which ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                e.preventDefault();
                return false;
            }
            return true;
        });
    }

    // Fungsi validasi jumlah
    function validateJumlah(input) {
        const max = parseInt(input.getAttribute('max') || 0);
        const value = parseInt(input.value || 0);
        
        console.log('Validasi jumlah input:', { max, value }); // Debug info
        
        if (max > 0 && value > max) {
            input.value = max;
            alert(`Jumlah maksimal yang dapat dipinjam adalah ${max} unit`);
            return false;
        }
        
        if (value < 1 && input.value !== '') {
            input.value = 1;
            return false;
        }
        
        return true;
    }

    function updateDurasi() {
        if (tanggalPinjam.value && tanggalKembali.value) {
            const start = new Date(tanggalPinjam.value);
            const end = new Date(tanggalKembali.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            durasiInfo.textContent = `Durasi: ${diffDays} hari`;
        }
    }

    // Update minimum date for return when borrow date changes
    tanggalPinjam.addEventListener('change', function() {
        const minReturn = new Date(this.value);
        minReturn.setDate(minReturn.getDate() + 1);
        tanggalKembali.min = minReturn.toISOString().split('T')[0];
        
        // Auto set return date to 7 days later if not set
        if (!tanggalKembali.value) {
            const autoReturn = new Date(this.value);
            autoReturn.setDate(autoReturn.getDate() + 7);
            tanggalKembali.value = autoReturn.toISOString().split('T')[0];
        }
        updateDurasi();
    });

    tanggalKembali.addEventListener('change', updateDurasi);

    // Initial duration calculation
    updateDurasi();
});
</script>
@endsection