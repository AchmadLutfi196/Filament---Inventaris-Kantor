<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Barang - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA BARANG INVENTARIS</h1>
        <p>Tanggal Export: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total Barang: {{ $barangs->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Kode</th>
                <th width="20%">Nama Barang</th>
                <th width="12%">Kategori</th>
                <th width="8%">Stok</th>
                <th width="10%">Kondisi</th>
                <th width="15%">Lokasi</th>
                <th width="10%">Status</th>
                <th width="15%">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama }}</td>
                <td>{{ $barang->kategori->nama }}</td>
                <td class="text-center">{{ $barang->stok_tersedia }}/{{ $barang->stok }}</td>
                <td>
                    <span class="badge badge-{{ $barang->kondisi == 'baik' ? 'success' : ($barang->kondisi == 'rusak ringan' ? 'warning' : 'danger') }}">
                        {{ ucfirst($barang->kondisi) }}
                    </span>
                </td>
                <td>{{ $barang->lokasi }}</td>
                <td>
                    <span class="badge badge-{{ $barang->tersedia ? 'success' : 'danger' }}">
                        {{ $barang->tersedia ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </td>
                <td>{{ $barang->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>