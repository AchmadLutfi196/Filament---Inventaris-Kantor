<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Export Data Inventaris</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Pilih jenis data yang ingin diekspor dan format file yang diinginkan. Anda dapat menerapkan filter untuk data yang lebih spesifik.
                </p>
            </div>
        </div>

        <!-- Form -->
        {{ $this->form }}

        <!-- Quick Export -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Export</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- All Data Excel -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">Semua Data Excel</h4>
                        <p class="text-sm text-gray-500 mt-1">Export semua data dalam format Excel</p>
                        <div class="mt-3 space-y-2">
                            @if(Route::has('admin.export.barang'))
                                <a href="{{ route('admin.export.barang', 'xlsx') }}" 
                                   class="block w-full text-center bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700">
                                    Barang (Excel)
                                </a>
                                <a href="{{ route('admin.export.peminjaman', 'xlsx') }}" 
                                   class="block w-full text-center bg-green-600 text-white py-1 px-3 rounded text-sm hover:bg-green-700">
                                    Peminjaman (Excel)
                                </a>
                            @else
                                <span class="text-xs text-red-500">Routes belum tersedia</span>
                            @endif
                        </div>
                    </div>

                    <!-- All Data PDF -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">Semua Data PDF</h4>
                        <p class="text-sm text-gray-500 mt-1">Export semua data dalam format PDF</p>
                        <div class="mt-3 space-y-2">
                            @if(Route::has('admin.export.barang'))
                                <a href="{{ route('admin.export.barang', 'pdf') }}" 
                                   class="block w-full text-center bg-red-600 text-white py-1 px-3 rounded text-sm hover:bg-red-700">
                                    Barang (PDF)
                                </a>
                                <a href="{{ route('admin.export.peminjaman', 'pdf') }}" 
                                   class="block w-full text-center bg-red-600 text-white py-1 px-3 rounded text-sm hover:bg-red-700">
                                    Peminjaman (PDF)
                                </a>
                            @else
                                <span class="text-xs text-red-500">Routes belum tersedia</span>
                            @endif
                        </div>
                    </div>

                    <!-- Laporan Lengkap -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900">Laporan Lengkap</h4>
                        <p class="text-sm text-gray-500 mt-1">Export laporan dengan ringkasan lengkap</p>
                        <div class="mt-3 space-y-2">
                            @if(Route::has('admin.export.laporan'))
                                <a href="{{ route('admin.export.laporan', 'xlsx') }}" 
                                   class="block w-full text-center bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                                    Laporan (Excel)
                                </a>
                                <a href="{{ route('admin.export.laporan', 'pdf') }}" 
                                   class="block w-full text-center bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                                    Laporan (PDF)
                                </a>
                            @else
                                <span class="text-xs text-red-500">Routes belum tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>