<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationLabel = 'Barang';
    
    protected static ?string $modelLabel = 'Barang';
    
    protected static ?string $pluralModelLabel = 'Barang';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama barang'),
                        
                        Forms\Components\TextInput::make('kode_barang')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: BRG-001')
                            ->helperText('Kode unik untuk barang'),
                        
                        Forms\Components\Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')
                                    ->required(),
                                Forms\Components\TextInput::make('kode')
                                    ->required(),
                                Forms\Components\Textarea::make('deskripsi'),
                            ]),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->maxLength(500)
                            ->placeholder('Deskripsi barang...')
                            ->rows(3),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Stok & Lokasi')
                    ->schema([
                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->helperText('Jumlah total barang'),
                        
                        Forms\Components\Select::make('kondisi')
                            ->required()
                            ->options([
                                'baik' => 'Baik',
                                'rusak ringan' => 'Rusak Ringan',
                                'perlu perbaikan' => 'Perlu Perbaikan',
                                'rusak berat' => 'Rusak Berat',
                            ])
                            ->default('baik'),
                        
                        Forms\Components\TextInput::make('lokasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Lantai 1, Ruang Server'),
                        
                        Forms\Components\Toggle::make('tersedia')
                            ->label('Tersedia untuk dipinjam')
                            ->default(true)
                            ->helperText('Matikan jika barang sedang tidak dapat dipinjam'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Foto Barang')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->directory('barang-photos')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(2048)
                            ->helperText('Upload foto barang (max 2MB)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.png')),
                
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('secondary'),
                
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok Total')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('stok_tersedia')
                    ->label('Stok Tersedia')
                    ->badge()
                    ->color(fn ($record) => match(true) {
                        $record->stok_tersedia <= 0 => 'danger',
                        $record->stok_tersedia <= 2 => 'warning',
                        default => 'success'
                    }),
                
                Tables\Columns\TextColumn::make('kondisi')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baik' => 'success',
                        'rusak ringan' => 'warning',
                        'perlu perbaikan' => 'danger',
                        'rusak berat' => 'danger',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('tersedia')
                    ->label('Tersedia')
                    ->boolean()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('kondisi')
                    ->options([
                        'baik' => 'Baik',
                        'rusak ringan' => 'Rusak Ringan',
                        'perlu perbaikan' => 'Perlu Perbaikan',
                        'rusak berat' => 'Rusak Berat',
                    ]),
                
                Tables\Filters\TernaryFilter::make('tersedia')
                    ->label('Ketersediaan')
                    ->placeholder('Semua')
                    ->trueLabel('Tersedia')
                    ->falseLabel('Tidak Tersedia'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->button()
                    ->form([
                        Forms\Components\Select::make('format')
                            ->label('Format Export')
                            ->options([
                                'xlsx' => 'Excel (.xlsx)',
                                'csv' => 'CSV (.csv)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('xlsx')
                            ->required(),
                        
                        Forms\Components\Toggle::make('filter_current')
                            ->label('Gunakan Filter Saat Ini')
                            ->default(true)
                            ->helperText('Export akan menggunakan filter dan pencarian yang sedang aktif'),
                    ])
                    ->action(function (array $data) {
                        $url = route('admin.export.barang', ['format' => $data['format']]);
                        
                        if ($data['filter_current']) {
                            $queryParams = request()->query();
                            $url .= '?' . http_build_query($queryParams);
                        }
                        
                        return redirect($url);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            // 'view' => Pages\ViewBarang::route('/{record}'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}