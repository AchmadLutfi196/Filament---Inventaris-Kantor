<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\BarangExport;
use App\Exports\PeminjamanExport;
use App\Exports\LaporanLengkapExport;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportBarang(Request $request, $format = 'xlsx')
    {
        $filename = 'data-barang-' . now()->format('Y-m-d-H-i-s');

        switch ($format) {
            case 'pdf':
                return $this->exportBarangToPdf($request, $filename);
            case 'csv':
                return Excel::download(new BarangExport($request), $filename . '.csv');
            default:
                return Excel::download(new BarangExport($request), $filename . '.xlsx');
        }
    }

    public function exportPeminjaman(Request $request, $format = 'xlsx')
    {
        $filename = 'data-peminjaman-' . now()->format('Y-m-d-H-i-s');

        switch ($format) {
            case 'pdf':
                return $this->exportPeminjamanToPdf($request, $filename);
            case 'csv':
                return Excel::download(new PeminjamanExport($request), $filename . '.csv');
            default:
                return Excel::download(new PeminjamanExport($request), $filename . '.xlsx');
        }
    }

    public function exportLaporanLengkap(Request $request, $format = 'xlsx')
    {
        $filename = 'laporan-lengkap-' . now()->format('Y-m-d-H-i-s');

        if ($format === 'pdf') {
            return $this->exportLaporanToPdf($request, $filename);
        }

        return Excel::download(new LaporanLengkapExport($request), $filename . '.xlsx');
    }

    private function exportBarangToPdf(Request $request, $filename)
    {
        $query = Barang::with(['kategori']);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('kategori')) {
            $query->byKategori($request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->byKondisi($request->kondisi);
        }

        $barangs = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('exports.barang-pdf', compact('barangs'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    private function exportPeminjamanToPdf(Request $request, $filename)
    {
        $query = Peminjaman::with(['user', 'barang', 'barang.kategori']);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('exports.peminjaman-pdf', compact('peminjamans'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    private function exportLaporanToPdf(Request $request, $filename)
    {
        $totalBarang = Barang::count();
        $barangTersedia = Barang::tersedia()->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::terlambat()->count();

        $data = compact('totalBarang', 'barangTersedia', 'peminjamanAktif', 'peminjamanTerlambat');

        $pdf = Pdf::loadView('exports.laporan-pdf', $data)->setPaper('a4');

        return $pdf->download($filename . '.pdf');
    }
}