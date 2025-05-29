<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $barangs = Barang::with('kategori')
                        ->tersedia()
                        ->search($query)
                        ->limit(10)
                        ->get()
                        ->map(function ($barang) {
                            return [
                                'id' => $barang->id,
                                'nama' => $barang->nama,
                                'kode_barang' => $barang->kode_barang,
                                'lokasi' => $barang->lokasi,
                                'stok_tersedia' => $barang->stok_tersedia,
                                'kategori' => [
                                    'id' => $barang->kategori->id,
                                    'nama' => $barang->kategori->nama
                                ]
                            ];
                        });

        return response()->json($barangs);
    }
}