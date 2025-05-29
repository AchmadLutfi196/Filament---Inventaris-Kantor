<?php

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

// Export routes untuk admin
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin/export')->name('admin.export.')->group(function () {
        Route::get('/barang/{format?}', [ExportController::class, 'exportBarang'])->name('barang');
        Route::get('/peminjaman/{format?}', [ExportController::class, 'exportPeminjaman'])->name('peminjaman');
        Route::get('/laporan/{format?}', [ExportController::class, 'exportLaporanLengkap'])->name('laporan');
    });
});