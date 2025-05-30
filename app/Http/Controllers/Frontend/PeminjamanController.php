<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil data peminjaman dengan pagination
            $peminjamans = Peminjaman::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            return view('frontend.peminjaman.index', compact('peminjamans'));
        } catch (\Exception $e) {
            // Tampilkan dengan data dummy jika error
            $dummyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                [], 
                0, 
                10, 
                1, 
                ['path' => $request->url(), 'query' => $request->query()]
            );
            return view('frontend.peminjaman.index', ['peminjamans' => $dummyPaginator]);
        }
    }

    public function create(Request $request)
    {
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
            return view('frontend.peminjaman.create', [
                'barangs' => [],
                'barang' => null 
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'barang_id' => 'required|exists:barangs,id',
                'jumlah' => 'required|integer|min:1',
                'tanggal_pinjam' => 'required|date',
                'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
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
            $peminjaman->user_id = Auth::id();
            $peminjaman->barang_id = $validated['barang_id'];
            $peminjaman->jumlah = $validated['jumlah'];
            $peminjaman->tanggal_pinjam = $validated['tanggal_pinjam'];
            $peminjaman->tanggal_kembali_rencana = $validated['tanggal_kembali_rencana'];
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
    }

    public function show($id)
    {
        try {
            // Ambil data peminjaman berdasarkan ID
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Verifikasi bahwa peminjaman ini milik user yang sedang login atau admin
            if ($peminjaman->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
                return redirect()->route('frontend.peminjaman.index')
                    ->with('error', 'Anda tidak memiliki akses ke peminjaman ini');
            }
            
            return view('frontend.peminjaman.show', compact('peminjaman'));
        } catch (\Exception $e) {
            // Redirect jika peminjaman tidak ditemukan
            return redirect()->route('frontend.peminjaman.index')
                ->with('error', 'Peminjaman tidak ditemukan');
        }
    }
}