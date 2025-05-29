<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Export Routes - TAMBAHKAN INI
Route::middleware(['auth'])->prefix('admin/export')->name('admin.export.')->group(function () {
    Route::get('/barang/{format?}', [ExportController::class, 'exportBarang'])->name('barang');
    Route::get('/peminjaman/{format?}', [ExportController::class, 'exportPeminjaman'])->name('peminjaman');
    Route::get('/laporan/{format?}', [ExportController::class, 'exportLaporanLengkap'])->name('laporan');
});

require __DIR__.'/auth.php';