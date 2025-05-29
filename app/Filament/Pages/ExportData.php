<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Models\Kategori;
use App\Models\User;

class ExportData extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    
    protected static string $view = 'filament.pages.export-data';
    
    protected static ?string $navigationLabel = 'Export Data';
    
    protected static ?string $title = 'Export Data';
    
    protected static ?int $navigationSort = 5;

    public ?array $barangData = [];
    public ?array $peminjamanData = [];
    public ?array $laporanData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Export Data Barang')
                    ->schema([
                        Select::make('barangData.format')
                            ->label('Format')
                            ->options([
                                'xlsx' => 'Excel (.xlsx)',
                                'csv' => 'CSV (.csv)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('xlsx'),
                        
                        Select::make('barangData.kategori')
                            ->label('Filter Kategori')
                            ->options(Kategori::pluck('nama', 'id'))
                            ->placeholder('Semua Kategori'),
                        
                        Select::make('barangData.kondisi')
                            ->label('Filter Kondisi')
                            ->options([
                                'baik' => 'Baik',
                                'rusak ringan' => 'Rusak Ringan',
                                'perlu perbaikan' => 'Perlu Perbaikan',
                            ])
                            ->placeholder('Semua Kondisi'),
                        
                        Toggle::make('barangData.tersedia_only')
                            ->label('Hanya Barang Tersedia'),
                        
                        Actions::make([
                            Action::make('exportBarang')
                                ->label('Export Data Barang')
                                ->color('primary')
                                ->action('exportBarang'),
                        ]),
                    ])->columns(2),

                Section::make('Export Data Peminjaman')
                    ->schema([
                        Select::make('peminjamanData.format')
                            ->label('Format')
                            ->options([
                                'xlsx' => 'Excel (.xlsx)',
                                'csv' => 'CSV (.csv)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('xlsx'),
                        
                        Select::make('peminjamanData.status')
                            ->label('Filter Status')
                            ->options([
                                'pending' => 'Menunggu Persetujuan',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'dipinjam' => 'Sedang Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                            ])
                            ->placeholder('Semua Status'),
                        
                        Select::make('peminjamanData.user_id')
                            ->label('Filter Pengguna')
                            ->options(User::where('role', 'user')->pluck('name', 'id'))
                            ->placeholder('Semua Pengguna')
                            ->searchable(),
                        
                        DatePicker::make('peminjamanData.dari_tanggal')
                            ->label('Dari Tanggal'),
                        
                        DatePicker::make('peminjamanData.sampai_tanggal')
                            ->label('Sampai Tanggal'),
                        
                        Actions::make([
                            Action::make('exportPeminjaman')
                                ->label('Export Data Peminjaman')
                                ->color('primary')
                                ->action('exportPeminjaman'),
                        ]),
                    ])->columns(2),

                Section::make('Export Laporan Lengkap')
                    ->schema([
                        Select::make('laporanData.format')
                            ->label('Format')
                            ->options([
                                'xlsx' => 'Excel (.xlsx)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('xlsx'),
                        
                        Actions::make([
                            Action::make('exportLaporan')
                                ->label('Export Laporan Lengkap')
                                ->color('primary')
                                ->action('exportLaporan'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function exportBarang(): void
    {
        $params = array_filter($this->barangData ?? []);
        $format = $params['format'] ?? 'xlsx';
        unset($params['format']);
        
        $url = route('admin.export.barang', ['format' => $format]);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $this->redirect($url);
    }

    public function exportPeminjaman(): void
    {
        $params = array_filter($this->peminjamanData ?? []);
        $format = $params['format'] ?? 'xlsx';
        unset($params['format']);
        
        $url = route('admin.export.peminjaman', ['format' => $format]);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $this->redirect($url);
    }

    public function exportLaporan(): void
    {
        $format = $this->laporanData['format'] ?? 'xlsx';
        $url = route('admin.export.laporan', ['format' => $format]);
        
        $this->redirect($url);
    }
}