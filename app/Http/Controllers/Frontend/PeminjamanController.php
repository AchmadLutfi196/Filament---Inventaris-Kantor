<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;   
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Redirect jika user tidak login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Query berdasarkan user yang login
        $query = Peminjaman::where('user_id', $user->id);
        
        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil data peminjaman
        $peminjamans = $query->with(['barang.kategori'])
                            ->latest('created_at')
                            ->paginate(10);
        
        return view('frontend.peminjaman.index', compact('peminjamans'));
    }

    public function create(Request $request)
    {
        $barang = null;
        
        if ($request->filled('barang_id')) {
            $barang = Barang::with('kategori')->findOrFail($request->barang_id);
        }

        return view('frontend.peminjaman.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
        ], [
            'barang_id.required' => 'Barang harus dipilih',
            'barang_id.exists' => 'Barang tidak ditemukan',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini',
            'tanggal_kembali_rencana.required' => 'Tanggal rencana kembali harus diisi',
            'tanggal_kembali_rencana.after' => 'Tanggal kembali harus setelah tanggal pinjam',
            'keperluan.required' => 'Keperluan harus diisi',
            'keperluan.max' => 'Keperluan maksimal 500 karakter',
        ]);

        // Cek ketersediaan stok
        $barang = Barang::findOrFail($request->barang_id);
        
        if ($request->jumlah > $barang->stok_tersedia) {
            return back()->withErrors([
                'jumlah' => 'Jumlah yang diminta melebihi stok tersedia (' . $barang->stok_tersedia . ' unit)'
            ])->withInput();
        }

        DB::transaction(function () use ($request) {
            Peminjaman::create([
                'user_id' => auth::id(),
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'keperluan' => $request->keperluan,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('frontend.peminjaman.index')
                        ->with('success', 'Permintaan peminjaman berhasil diajukan!');
    }

    public function show(Peminjaman $peminjaman)
    {
        // Pastikan user hanya bisa melihat peminjaman sendiri
        if ($peminjaman->user_id !== auth::id()) {
            abort(403);
        }

        $peminjaman->load(['barang', 'barang.kategori']);

        return view('frontend.peminjaman.show', compact('peminjaman'));
    }
}