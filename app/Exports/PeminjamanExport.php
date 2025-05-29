<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;
    protected $userId;

    public function __construct(Request $request = null, $userId = null)
    {
        $this->request = $request;
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = Peminjaman::with(['user', 'barang', 'barang.kategori']);

        // Filter by user if specified (for user export)
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        // Apply filters if request is provided
        if ($this->request) {
            if ($this->request->filled('status')) {
                $query->byStatus($this->request->status);
            }

            if ($this->request->filled('user_id') && !$this->userId) {
                $query->byUser($this->request->user_id);
            }

            if ($this->request->filled('barang_id')) {
                $query->byBarang($this->request->barang_id);
            }

            if ($this->request->filled('dari_tanggal') && $this->request->filled('sampai_tanggal')) {
                $query->byDateRange($this->request->dari_tanggal, $this->request->sampai_tanggal);
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Peminjaman',
            'Peminjam',
            'Barang',
            'Kategori',
            'Jumlah',
            'Tanggal Pinjam',
            'Tanggal Rencana Kembali',
            'Tanggal Kembali Aktual',
            'Status',
            'Durasi (Hari)',
            'Keperluan',
            'Catatan Admin',
            'Tanggal Dibuat',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->kode_peminjaman,
            $peminjaman->user->name,
            $peminjaman->barang->nama,
            $peminjaman->barang->kategori->nama,
            $peminjaman->jumlah,
            $peminjaman->tanggal_pinjam->format('d/m/Y'),
            $peminjaman->tanggal_kembali_rencana->format('d/m/Y'),
            $peminjaman->tanggal_kembali_aktual ? $peminjaman->tanggal_kembali_aktual->format('d/m/Y') : '-',
            $this->getStatusLabel($peminjaman->status),
            $peminjaman->durasi,
            $peminjaman->keperluan,
            $peminjaman->catatan_admin ?: '-',
            $peminjaman->created_at->format('d/m/Y H:i'),
        ];
    }

    private function getStatusLabel($status): string
    {
        return match($status) {
            'pending' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipinjam' => 'Sedang Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            default => $status
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}