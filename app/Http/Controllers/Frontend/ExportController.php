<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Exports\PeminjamanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class ExportController extends Controller
{
    public function exportPeminjaman(Request $request, $format = 'xlsx')
    {
        $filename = 'peminjaman-saya-' . now()->format('Y-m-d-H-i-s');
        $userId = auth::id();

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($request, $filename, $userId);
            case 'csv':
                return Excel::download(new PeminjamanExport($request, $userId), $filename . '.csv');
            default:
                return Excel::download(new PeminjamanExport($request, $userId), $filename . '.xlsx');
        }
    }

    private function exportToPdf(Request $request, $filename, $userId)
    {
        $peminjamans = Auth::user()->peminjamans()
                                  ->with(['barang', 'barang.kategori'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        $pdf = Pdf::loadView('exports.user-peminjaman-pdf', compact('peminjamans'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }
}