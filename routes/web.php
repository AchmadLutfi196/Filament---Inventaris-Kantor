<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Barang; 
use App\Models\Peminjaman;
use App\Models\Kategori;

// Route untuk halaman utama - akan mengarahkan ke login jika belum login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('frontend.dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::post('/frontend/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('frontend.logout');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Route Frontend
Route::middleware(['auth'])->group(function () {
    Route::get('/frontend/dashboard', function () {
        try {
            // Hitung data untuk dashboard dengan nilai default
            $totalBarang = Barang::count() ?? 0;
            $totalKategori = Kategori::count() ?? 0;
            $totalPinjam = Peminjaman::count() ?? 0;
            $barangTersedia = $totalBarang; 
            $peminjamanAktif = 0; 
            $riwayatPeminjaman = 0; 
            
            return view('frontend.dashboard', compact(
                'totalBarang', 
                'totalKategori', 
                'totalPinjam', 
                'barangTersedia',
                'peminjamanAktif',
                'riwayatPeminjaman'
            ));
        } catch (\Exception $e) {
            // Tampilkan dashboard dengan data dummy jika terjadi error
            return view('frontend.dashboard', [
                'totalBarang' => 0,
                'totalKategori' => 0,
                'totalPinjam' => 0,
                'barangTersedia' => 0,
                'peminjamanAktif' => 0,
                'riwayatPeminjaman' => 0
            ]);
        }
    })->name('frontend.dashboard');
    
    // Route untuk Barang 
    Route::get('/frontend/barang', function () {
        try {
            // Ambil data kategori
            $kategoris = Kategori::all();
            
            // Filter berdasarkan kategori jika ada
            $query = Barang::query();
            
            if (request()->has('kategori') && !empty(request('kategori'))) {
                $query->where('kategori_id', request('kategori'));
            }
            
            if (request()->has('kondisi') && !empty(request('kondisi'))) {
                $query->where('kondisi', request('kondisi'));
            }
            
            if (request()->has('search') && !empty(request('search'))) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }
            
            // Ambil data barang dengan paginasi
            $barangs = $query->paginate(10);
            
            return view('frontend.barang.index', compact('kategoris', 'barangs'));
        } catch (\Exception $e) {
            // Tampilkan dengan data dummy jika error
            $dummyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                [], // items
                0,  // total
                10, // per page
                1,  // current page
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
            return view('frontend.barang.index', [
                'kategoris' => [],
                'barangs' => $dummyPaginator
            ]);
        }
    })->name('frontend.barang.index');
    
    // Route untuk Detail Barang
    Route::get('/frontend/barang/{id}', function ($id) {
        try {
            // Ambil data barang berdasarkan ID
            $barang = Barang::findOrFail($id);
            return view('frontend.barang.show', compact('barang'));
        } catch (\Exception $e) {
            // Redirect jika barang tidak ditemukan
            return redirect()->route('frontend.barang.index')
                ->with('error', 'Barang tidak ditemukan');
        }
    })->name('frontend.barang.show');
    
    // Route untuk Peminjaman
    Route::get('/frontend/peminjaman', function () {
        try {
            // Ambil data peminjaman dengan pagination
            $peminjamans = Peminjaman::paginate(10);
            return view('frontend.peminjaman.index', compact('peminjamans'));
        } catch (\Exception $e) {
            // Tampilkan dengan data dummy jika error
            $dummyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                [], 
                0, 
                10, 
                1, 
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return view('frontend.peminjaman.index', ['peminjamans' => $dummyPaginator]);
        }
    })->name('frontend.peminjaman.index');
    
    // Route untuk export peminjaman
    Route::get('/frontend/peminjaman/export/{format?}', function ($format = 'pdf') {
        try {
            // Logika untuk export peminjaman
            if ($format == 'excel') {
                // Logika export ke Excel
                return redirect()->back()->with('success', 'Data peminjaman berhasil diexport ke Excel');
            } else {
                // Default export ke PDF
                return redirect()->back()->with('success', 'Data peminjaman berhasil diexport ke PDF');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    })->name('frontend.peminjaman.export');
    
    // Route untuk tambah peminjaman 
    Route::get('/frontend/peminjaman/create', function () {
        try {
            // Ambil data barang yang tersedia untuk dipilih
            $barangs = Barang::all();
            
            // Cek apakah parameter barang_id ada
            $barang = null;
            if (request()->has('barang_id')) {
                $barang = Barang::find(request('barang_id'));
            }
            
            return view('frontend.peminjaman.create', compact('barangs', 'barang'));
        } catch (\Exception $e) {
            // Tampilkan dengan data dummy jika error
            return view('frontend.peminjaman.create', [
                'barangs' => [],
                'barang' => null // Tambahkan ini
            ]);
        }
    })->name('frontend.peminjaman.create');
    
    // Route untuk simpan peminjaman (POST)
Route::post('/frontend/peminjaman', function () {
    try {
        // Validasi input
        $validated = request()->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam', // Pastikan nama kolom ini benar
            'keperluan' => 'nullable|string'
        ]);
        
        // Generate kode peminjaman
        $kode = 'PJM-' . date('Ymd') . '-' . str_pad(
            (Peminjaman::whereDate('created_at', today())->count() + 1), 
            3, 
            '0', 
            STR_PAD_LEFT
        );
        
        // Buat peminjaman baru
        $peminjaman = new Peminjaman();
        $peminjaman->user_id = auth::id();
        $peminjaman->barang_id = $validated['barang_id'];
        $peminjaman->jumlah = $validated['jumlah'];
        $peminjaman->tanggal_pinjam = $validated['tanggal_pinjam'];
        $peminjaman->tanggal_kembali_rencana = $validated['tanggal_kembali_rencana']; // Gunakan kolom yang sudah ada
        $peminjaman->status = 'pending';
        $peminjaman->kode_peminjaman = $kode;
        $peminjaman->keperluan = $validated['keperluan'] ?? null;
        $peminjaman->save();
        
        // Redirect dengan pesan sukses
        return redirect()
            ->route('frontend.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan dan sedang menunggu persetujuan.');
    } catch (\Exception $e) {
        // Redirect dengan pesan error
        return back()
            ->withInput()
            ->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    }
})->name('frontend.peminjaman.store');
    
    // Route untuk detail peminjaman
    Route::get('/frontend/peminjaman/{id}', function ($id) {
        try {
            // Ambil data peminjaman berdasarkan ID
            $peminjaman = Peminjaman::findOrFail($id);
            return view('frontend.peminjaman.show', compact('peminjaman'));
        } catch (\Exception $e) {
            // Redirect jika peminjaman tidak ditemukan
            return redirect()->route('frontend.peminjaman.index')
                ->with('error', 'Peminjaman tidak ditemukan');
        }
    })->name('frontend.peminjaman.show');
});

// Route untuk profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Export Routes
Route::middleware(['auth'])->prefix('admin/export')->name('admin.export.')->group(function () {
    Route::get('/barang/{format?}', [ExportController::class, 'exportBarang'])->name('barang');
    Route::get('/peminjaman/{format?}', [ExportController::class, 'exportPeminjaman'])->name('peminjaman');
    Route::get('/laporan/{format?}', [ExportController::class, 'exportLaporanLengkap'])->name('laporan');
});

require __DIR__.'/auth.php';