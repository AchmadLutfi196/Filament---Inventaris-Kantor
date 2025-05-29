<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Peminjaman Saya - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { padding: 2px 4px; border-radius: 3px; font-size: 9px; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-info { background-color: #cce7ff; color: #004085; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RIWAYAT PEMINJAMAN SAYA</h1>
        <p>Nama: {{ auth()->user()->name }}</p>
        <p>Tanggal Export: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total Peminjaman: {{ $peminjamans->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Kode</th>
                <th width="25%">Barang</th>
                <th width="10%">Jumlah</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Rencana Kembali</th>
                <th width="15%">Status</th>
                <th width="11%">Durasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $peminjaman)
            <tr>
                <td>{{ $peminjaman->kode_peminjaman }}</td>
                <td>{{ $peminjaman->barang->nama }}</td>
                <td class="text-center">{{ $peminjaman->jumlah }}</td>
                <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                <td>
                    <span class="badge badge-{{ 
                        $peminjaman->status == 'pending' ? 'warning' : 
                        ($peminjaman->status == 'disetujui' ? 'info' : 
                        ($peminjaman->status == 'ditolak' ? 'danger' : 
                        ($peminjaman->status == 'dipinjam' ? 'success' : 'secondary'))) 
                    }}">
                        {{ match($peminjaman->status) {
                            'pending' => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            'dipinjam' => 'Dipinjam',
                            'dikembalikan' => 'Kembali',
                            default => $peminjaman->status
                        } }}
                    </span>
                </td>
                <td class="text-center">{{ $peminjaman->durasi }} hari</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <p><strong>Keterangan:</strong></p>
        <p>• Menunggu: Menunggu persetujuan admin</p>
        <p>• Disetujui: Peminjaman telah disetujui, belum diambil</p>
        <p>• Dipinjam: Barang sedang dipinjam</p>
        <p>• Kembali: Barang telah dikembalikan</p>
        <p>• Ditolak: Peminjaman ditolak oleh admin</p>
    </div>
</body>
</html>