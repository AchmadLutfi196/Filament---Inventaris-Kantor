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
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('kode_barang')
                            ->label('Kode Barang')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Contoh: BRG001'),
                        
                        Forms\Components\Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(3)
                            ->columnSpan(2),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail & Harga')
                    ->schema([
                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->helperText('Jumlah total barang'),
                        
                        Forms\Components\TextInput::make('harga_sewa_per_hari')
                            ->label('Harga Sewa/Hari')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->step(1000)
                            ->helperText('Biaya sewa per hari per unit'),
                        
                        Forms\Components\TextInput::make('biaya_deposit')
                            ->label('Biaya Deposit')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->step(5000)
                            ->helperText('Deposit yang harus dibayar per unit'),
                        
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
                
                Forms\Components\Section::make('Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->directory('barang-photos')
                            ->maxSize(2048)
                            ->helperText('Maksimal 2MB, format: JPG, PNG, GIF'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->defaultImageUrl(asset('images/no-image.png')),
                
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('harga_sewa_per_hari')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('biaya_deposit')
                    ->label('Deposit')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stok')
                    ->alignCenter()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stok_tersedia')
                    ->label('Tersedia')
                    ->alignCenter()
                    ->getStateUsing(fn (Barang $record): int => $record->stok_tersedia),
                
                Tables\Columns\BadgeColumn::make('kondisi')
                    ->colors([
                        'success' => 'baik',
                        'warning' => 'rusak ringan',
                        'warning' => 'perlu perbaikan',
                        'danger' => 'rusak berat',
                    ]),
                
                Tables\Columns\IconColumn::make('tersedia')
                    ->boolean()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama'),
                
                Tables\Filters\SelectFilter::make('kondisi')
                    ->options([
                        'baik' => 'Baik',
                        'rusak ringan' => 'Rusak Ringan',
                        'perlu perbaikan' => 'Perlu Perbaikan',
                        'rusak berat' => 'Rusak Berat',
                    ]),
                
                Tables\Filters\TernaryFilter::make('tersedia')
                    ->label('Status Ketersediaan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}