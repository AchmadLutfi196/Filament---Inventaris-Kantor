<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil data kategori
            $kategoris = Kategori::all();
            
            // Filter berdasarkan kategori jika ada
            $query = Barang::query();
            
            if ($request->has('kategori') && !empty($request->kategori)) {
                $query->where('kategori_id', $request->kategori);
            }
            
            if ($request->has('kondisi') && !empty($request->kondisi)) {
                $query->where('kondisi', $request->kondisi);
            }
            
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
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
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('frontend.barang.index', [
                'kategoris' => [],
                'barangs' => $dummyPaginator
            ]);
        }
    }

    public function show($id)
    {
        try {
            // Ambil data barang berdasarkan ID
            $barang = Barang::findOrFail($id);
            return view('frontend.barang.show', compact('barang'));
        } catch (\Exception $e) {
            
            // Redirect jika barang tidak ditemukan
            return redirect()->route('frontend.barang.index')
                ->with('error', 'Barang tidak ditemukan');
        }
    }
}