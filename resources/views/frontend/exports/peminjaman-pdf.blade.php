<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peminjaman - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
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
        <h1>DATA PEMINJAMAN BARANG</h1>
        <p>Tanggal Export: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total Peminjaman: {{ $peminjamans->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Kode</th>
                <th width="15%">Peminjam</th>
                <th width="18%">Barang</th>
                <th width="8%">Jumlah</th>
                <th width="10%">Tgl Pinjam</th>
                <th width="10%">Rencana Kembali</th>
                <th width="12%">Status</th>
                <th width="15%">Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $peminjaman)
            <tr>
                <td>{{ $peminjaman->kode_peminjaman }}</td>
                <td>{{ $peminjaman->user->name }}</td>
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
                <td>{{ Str::limit($peminjaman->keperluan, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>