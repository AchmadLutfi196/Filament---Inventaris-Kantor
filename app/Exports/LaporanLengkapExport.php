<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanLengkapExport implements WithMultipleSheets
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new RingkasanSheet($this->request),
            'Data Barang' => new BarangExport($this->request),
            'Data Peminjaman' => new PeminjamanExport($this->request),
        ];
    }
}