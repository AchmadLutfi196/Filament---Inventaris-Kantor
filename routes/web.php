<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\BarangController;
use App\Http\Controllers\Frontend\PeminjamanController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::post('/frontend/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('frontend.logout');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Frontend routes (requires auth)
Route::middleware(['auth'])->prefix('frontend')->name('frontend.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Barang Routes
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.show');
    
    // Peminjaman Routes
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    
    // Payment Routes
    Route::get('/peminjaman/{id}/payment', [PeminjamanController::class, 'payment'])->name('peminjaman.payment');
    Route::post('/peminjaman/get-barang-details', [PeminjamanController::class, 'getBarangDetails'])->name('peminjaman.get-barang-details');
    
    // Midtrans callback routes
    Route::get('/payment/finish', [PeminjamanController::class, 'paymentFinish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PeminjamanController::class, 'paymentUnfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PeminjamanController::class, 'paymentError'])->name('payment.error');
    
    // Chat Routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
});


// Midtrans webhook (no auth needed, global)
Route::post('/midtrans/callback', [PeminjamanController::class, 'paymentCallback'])->name('midtrans.callback');

require __DIR__.'/auth.php';