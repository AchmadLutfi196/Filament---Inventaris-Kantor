<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        
        // Total barang tersedia
        $totalBarang = Barang::tersedia()->count();
        
        // Total kategori
        $totalKategori = Kategori::count();
        
        // Peminjaman aktif: pending, disetujui, dipinjam
        $peminjamanAktif = Peminjaman::where('user_id', $userId)
                                ->whereIn('status', ['pending', 'disetujui', 'dipinjam'])
                                ->count();
        
        // Riwayat peminjaman: hanya yang sudah selesai/ditolak/dibatalkan
        $riwayatPeminjaman = Peminjaman::where('user_id', $userId)
                                    ->whereIn('status', ['dikembalikan', 'selesai', 'ditolak', 'dibatalkan'])
                                    ->count();

        return view('frontend.dashboard', compact(
            'totalBarang',
            'totalKategori', 
            'peminjamanAktif',
            'riwayatPeminjaman',
            'debugInfo'
        ));
    }
}