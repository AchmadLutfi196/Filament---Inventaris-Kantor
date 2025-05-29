<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Barang::with(['kategori']);

        // Apply filters if request is provided
        if ($this->request) {
            if ($this->request->filled('search')) {
                $query->search($this->request->search);
            }

            if ($this->request->filled('kategori')) {
                $query->byKategori($this->request->kategori);
            }

            if ($this->request->filled('kondisi')) {
                $query->byKondisi($this->request->kondisi);
            }

            if ($this->request->filled('tersedia')) {
                if ($this->request->tersedia === 'true') {
                    $query->where('tersedia', true);
                } elseif ($this->request->tersedia === 'false') {
                    $query->where('tersedia', false);
                }
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Deskripsi',
            'Stok Total',
            'Stok Tersedia',
            'Kondisi',
            'Lokasi',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->kode_barang,
            $barang->nama,
            $barang->kategori->nama,
            $barang->deskripsi,
            $barang->stok,
            $barang->stok_tersedia,
            ucfirst($barang->kondisi),
            $barang->lokasi,
            $barang->tersedia ? 'Tersedia' : 'Tidak Tersedia',
            $barang->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}