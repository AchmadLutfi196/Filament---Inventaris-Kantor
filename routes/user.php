<?php

use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\BarangController;
use App\Http\Controllers\Frontend\PeminjamanController;
use App\Http\Controllers\Frontend\ChatController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');

// Auth routes untuk user biasa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    
    // Chat routes - Remove this since it's defined in web.php
    // Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
});

// Include auth routes
require __DIR__.'/auth.php';