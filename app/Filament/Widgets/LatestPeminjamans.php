<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPeminjamans extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Peminjaman Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peminjaman::query()->with(['user', 'barang'])->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_peminjaman')
                    ->label('Kode')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam'),
                
                Tables\Columns\TextColumn::make('barang.nama')
                    ->label('Barang')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'disetujui' => 'info',
                        'ditolak' => 'danger',
                        'dipinjam' => 'success',
                        'dikembalikan' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal')
                    ->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (Peminjaman $record): string => route('filament.admin.resources.peminjamans.view', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}