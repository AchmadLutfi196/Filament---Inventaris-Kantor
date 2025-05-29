<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Export
        </x-slot>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Export Barang -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-cube class="w-8 h-8 text-blue-600"/>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Data Barang</h3>
                        <p class="text-xs text-gray-500">Export semua data barang</p>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('admin.export.barang', 'xlsx') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                        Excel
                    </a>
                    <a href="{{ route('admin.export.barang', 'pdf') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                        PDF
                    </a>
                    <a href="{{ route('admin.export.barang', 'csv') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-gray-600 hover:bg-gray-700">
                        CSV
                    </a>
                </div>
            </div>

            <!-- Export Peminjaman -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-purple-600"/>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Data Peminjaman</h3>
                        <p class="text-xs text-gray-500">Export semua data peminjaman</p>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('admin.export.peminjaman', 'xlsx') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                        Excel
                    </a>
                    <a href="{{ route('admin.export.peminjaman', 'pdf') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                        PDF
                    </a>
                    <a href="{{ route('admin.export.peminjaman', 'csv') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-gray-600 hover:bg-gray-700">
                        CSV
                    </a>
                </div>
            </div>

            <!-- Export Laporan Lengkap -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-document-chart-bar class="w-8 h-8 text-yellow-600"/>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Laporan Lengkap</h3>
                        <p class="text-xs text-gray-500">Export laporan dengan ringkasan</p>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('admin.export.laporan', 'xlsx') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                        Excel
                    </a>
                    <a href="{{ route('admin.export.laporan', 'pdf') }}" 
                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>