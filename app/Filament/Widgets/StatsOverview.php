<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Barang', Barang::count())
                ->description('Barang terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            
            Stat::make('Barang Tersedia', Barang::tersedia()->count())
                ->description('Siap dipinjam')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Sedang Dipinjam', Peminjaman::where('status', 'dipinjam')->count())
                ->description('Peminjaman aktif')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color('warning'),
            
            Stat::make('Pengguna Aktif', User::where('is_active', true)->where('role', 'user')->count())
                ->description('User terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}