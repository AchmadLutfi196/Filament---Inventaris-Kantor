<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Hitung data untuk dashboard dengan nilai default
            $totalBarang = Barang::count() ?? 0;
            $totalKategori = Kategori::count() ?? 0;
            $totalPinjam = Peminjaman::count() ?? 0;
            $barangTersedia = $totalBarang; 
            
            // Hitung peminjaman aktif: pending, disetujui, dipinjam
            $peminjamanAktif = Peminjaman::where('user_id', Auth::id())
                                        ->whereIn('status', ['pending', 'disetujui', 'dipinjam'])
                                        ->count() ?? 0;
            
            // Hitung riwayat peminjaman: yang sudah selesai/dikembalikan/ditolak
            $riwayatPeminjaman = Peminjaman::where('user_id', Auth::id())
                                        ->whereIn('status', ['dikembalikan', 'selesai', 'ditolak', 'dibatalkan', 'dipinjam', 'pending'])
                                        ->count() ?? 0; 
            
            return view('frontend.dashboard', compact(
                'totalBarang', 
                'totalKategori', 
                'totalPinjam', 
                'barangTersedia',
                'peminjamanAktif',
                'riwayatPeminjaman'
            ));
        } catch (\Exception $e) {
            return view('frontend.dashboard', [
                'totalBarang' => 0,
                'totalKategori' => 0,
                'totalPinjam' => 0,
                'barangTersedia' => 0,
                'peminjamanAktif' => 0,
                'riwayatPeminjaman' => 0
            ]);
        }
    }
}