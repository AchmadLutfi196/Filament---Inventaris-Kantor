<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarang = Barang::tersedia()->count();
        $totalKategori = Kategori::count();
        $peminjamanAktif = Peminjaman::where('user_id', Auth::id())
                                    ->whereIn('status', ['pending', 'disetujui', 'dipinjam'])
                                    ->count();
        $riwayatPeminjaman = Peminjaman::where('user_id', Auth::id())->count();

        return view('frontend.dashboard', compact(
            'totalBarang',
            'totalKategori', 
            'peminjamanAktif',
            'riwayatPeminjaman'
        ));
    }
}