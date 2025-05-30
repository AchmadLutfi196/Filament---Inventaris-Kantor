<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\BarangController;
use App\Http\Controllers\Frontend\PeminjamanController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/frontend/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('frontend.logout');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Route Frontend
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/frontend/dashboard', [DashboardController::class, 'index'])->name('frontend.dashboard');
    
    // Barang Routes
    Route::get('/frontend/barang', [BarangController::class, 'index'])->name('frontend.barang.index');
    Route::get('/frontend/barang/{id}', [BarangController::class, 'show'])->name('frontend.barang.show');
    
    // Peminjaman Routes
    Route::get('/frontend/peminjaman', [PeminjamanController::class, 'index'])->name('frontend.peminjaman.index');
    Route::get('/frontend/peminjaman/create', [PeminjamanController::class, 'create'])->name('frontend.peminjaman.create');
    Route::post('/frontend/peminjaman', [PeminjamanController::class, 'store'])->name('frontend.peminjaman.store');
    Route::get('/frontend/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('frontend.peminjaman.show');
});

// Uncomment jika perlu
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';