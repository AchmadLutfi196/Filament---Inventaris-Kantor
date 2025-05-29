<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RingkasanSheet implements FromArray, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        $totalBarang = Barang::count();
        $barangTersedia = Barang::tersedia()->count();
        $totalKategori = Kategori::count();
        $totalUser = User::where('role', 'user')->where('is_active', true)->count();
        
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::terlambat()->count();
        $totalPeminjaman = Peminjaman::count();

        return [
            ['LAPORAN INVENTARIS KANTOR'],
            ['Tanggal Export: ' . now()->format('d/m/Y H:i')],
            [],
            ['RINGKASAN DATA'],
            [],
            ['BARANG'],
            ['Total Barang', $totalBarang],
            ['Barang Tersedia', $barangTersedia],
            ['Barang Tidak Tersedia', $totalBarang - $barangTersedia],
            ['Total Kategori', $totalKategori],
            [],
            ['PENGGUNA'],
            ['Total Pengguna Aktif', $totalUser],
            [],
            ['PEMINJAMAN'],
            ['Total Peminjaman', $totalPeminjaman],
            ['Menunggu Persetujuan', $peminjamanPending],
            ['Sedang Dipinjam', $peminjamanAktif],
            ['Terlambat', $peminjamanTerlambat],
            [],
            ['KATEGORI POPULER'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            4 => ['font' => ['bold' => true, 'size' => 14]],
            6 => ['font' => ['bold' => true]],
            12 => ['font' => ['bold' => true]],
            15 => ['font' => ['bold' => true]],
            21 => ['font' => ['bold' => true]],
        ];
    }
}