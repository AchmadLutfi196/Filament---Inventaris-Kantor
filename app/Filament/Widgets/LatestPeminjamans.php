<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPeminjamans extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peminjaman::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_peminjaman')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->label('Tgl Pinjam')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('tanggal_kembali_rencana')
                    ->date('d M Y')
                    ->label('Rencana Kembali')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            ])
            // Hapus actions yang menyebabkan error
            ->actions([])
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc');
    }
}