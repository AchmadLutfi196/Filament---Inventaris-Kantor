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
                    <!-- Pilih Barang -->
                    <div class="space-y-4">
                        <label for="barang_search" class="block text-sm font-medium text-gray-700">Cari Barang</label>
                        <div class="relative">
                            <input type="text" id="barang_search" placeholder="Ketik nama atau kode barang..." 
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <div id="barang_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-auto"></div>
                        </div>
                        <input type="hidden" name="barang_id" id="selected_barang_id" value="{{ old('barang_id') }}">
                        <div id="selected_barang" class="hidden">
                            <!-- Selected barang will be shown here -->
                        </div>
                        @error('barang_id')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-sm text-gray-500">
                            Atau <a href="{{ route('frontend.barang.index') }}" class="text-blue-600 hover:text-blue-800">lihat daftar semua barang</a>
                        </p>
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
                    <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}" 
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
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const durasiInfo = document.getElementById('durasi_info');

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

    // Barang search functionality (jika tidak ada barang yang dipilih)
    @if(!$barang)
    const searchInput = document.getElementById('barang_search');
    const resultsDiv = document.getElementById('barang_results');
    const selectedBarangId = document.getElementById('selected_barang_id');
    const selectedBarangDiv = document.getElementById('selected_barang');

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/barang/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-3 text-gray-500">Tidak ada barang ditemukan</div>';
                    } else {
                        data.forEach(barang => {
                            const div = document.createElement('div');
                            div.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100';
                            div.innerHTML = `
                                <div class="flex items-center space-x-3">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">${barang.nama}</h4>
                                        <p class="text-sm text-gray-600">${barang.kode_barang} - ${barang.kategori.nama}</p>
                                        <p class="text-sm text-green-600">Stok: ${barang.stok_tersedia} unit</p>
                                    </div>
                                </div>
                            `;
                            div.addEventListener('click', () => selectBarang(barang));
                            resultsDiv.appendChild(div);
                        });
                    }
                    
                    resultsDiv.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }, 300);
    });

    function selectBarang(barang) {
        selectedBarangId.value = barang.id;
        searchInput.value = barang.nama;
        resultsDiv.classList.add('hidden');
        
        selectedBarangDiv.innerHTML = `
            <div class="border border-gray-200 rounded-lg p-4 bg-blue-50">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900">${barang.nama}</h4>
                        <p class="text-sm text-gray-600">${barang.kode_barang} - ${barang.kategori.nama}</p>
                        <p class="text-sm text-gray-600">Lokasi: ${barang.lokasi}</p>
                        <p class="text-sm font-medium text-green-600">Stok Tersedia: ${barang.stok_tersedia} unit</p>
                    </div>
                    <button type="button" onclick="clearSelection()" class="text-red-600 hover:text-red-800 text-sm">
                        Hapus
                    </button>
                </div>
            </div>
        `;
        selectedBarangDiv.classList.remove('hidden');
        
        // Update jumlah max
        document.getElementById('jumlah').setAttribute('max', barang.stok_tersedia);
    }

    window.clearSelection = function() {
        selectedBarangId.value = '';
        searchInput.value = '';
        selectedBarangDiv.classList.add('hidden');
        document.getElementById('jumlah').removeAttribute('max');
    };

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.classList.add('hidden');
        }
    });
    @endif

    // Initial duration calculation
    updateDurasi();
});
</script>
@endsection