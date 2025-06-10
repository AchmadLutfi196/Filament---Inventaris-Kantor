<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\BarangController;
use App\Http\Controllers\Frontend\PeminjamanController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Middleware\DomainMiddleware;
use Illuminate\Support\Facades\Route;

// Apply domain middleware to all routes
Route::middleware([DomainMiddleware::class])->group(function () {
    
    // User Domain Routes (user.inventaris.local)
    Route::domain(env('USER_DOMAIN', 'user.inventaris.local'))->group(function () {
        // Route untuk halaman utama user
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::post('/frontend/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('frontend.logout');

        Route::middleware('guest')->group(function () {
            Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
            Route::post('register', [RegisteredUserController::class, 'store']);
        });

        // Route Frontend (User only)
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
            
            // Chat Routes
            Route::get('/frontend/chat', [ChatController::class, 'index'])->name('frontend.chat.index');
        });
    });

    // Admin Domain Routes (admin.inventaris.local)
    Route::domain(env('ADMIN_DOMAIN', 'admin.inventaris.local'))->group(function () {
        // Redirect root to admin panel
        Route::get('/', function () {
            return redirect('/admin');
        });
    });

    // Fallback routes for localhost development
    Route::get('/', function () {
        $domain = request()->getHost();
        if (str_contains($domain, '8080') || session('domain_type') === 'admin') {
            return redirect('/admin');
        }
        return app(HomeController::class)->index();
    })->name('home.fallback');

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
        
        // Chat Routes
        Route::get('/frontend/chat', [ChatController::class, 'index'])->name('frontend.chat.index');
    });
});

require __DIR__.'/auth.php';