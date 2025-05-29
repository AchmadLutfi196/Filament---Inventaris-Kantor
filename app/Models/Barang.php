<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';
    
    protected $fillable = [
        'nama',
        'kode_barang',
        'kategori_id',
        'deskripsi',
        'stok',
        'kondisi',
        'lokasi',
        'foto',
        'tersedia'
    ];

    protected $casts = [
        'tersedia' => 'boolean',
        'stok' => 'integer'
    ];

    /**
     * Relasi dengan Kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi dengan Peminjaman
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi peminjaman yang sedang aktif
     */
    public function peminjamansAktif(): HasMany
    {
        return $this->hasMany(Peminjaman::class)
                    ->whereIn('status', ['disetujui', 'dipinjam']);
    }

    /**
     * Accessor untuk stok tersedia
     */
    public function getStokTersediaAttribute(): int
    {
        $stokDipinjam = $this->peminjamansAktif()->sum('jumlah');
        return $this->stok - $stokDipinjam;
    }

    /**
     * Accessor untuk status ketersediaan
     */
    public function getStatusKetersediaanAttribute(): string
    {
        if (!$this->tersedia) {
            return 'Tidak Tersedia';
        }
        
        if ($this->stok_tersedia <= 0) {
            return 'Habis';
        }
        
        if ($this->stok_tersedia <= 2) {
            return 'Stok Menipis';
        }
        
        return 'Tersedia';
    }

    /**
     * Accessor untuk warna status
     */
    public function getWarnaStatusAttribute(): string
    {
        return match($this->status_ketersediaan) {
            'Tidak Tersedia' => 'red',
            'Habis' => 'red',
            'Stok Menipis' => 'yellow',
            'Tersedia' => 'green',
            default => 'gray'
        };
    }

    /**
     * Scope untuk barang tersedia
     */
    public function scopeTersedia(Builder $query): void
    {
        $query->where('tersedia', true)
              ->whereRaw('stok > (
                  SELECT COALESCE(SUM(jumlah), 0) 
                  FROM peminjamans 
                  WHERE barang_id = barangs.id 
                  AND status IN ("disetujui", "dipinjam")
              )');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch(Builder $query, $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', '%' . $search . '%')
              ->orWhere('kode_barang', 'like', '%' . $search . '%')
              ->orWhere('lokasi', 'like', '%' . $search . '%')
              ->orWhereHas('kategori', function ($kategoriQuery) use ($search) {
                  $kategoriQuery->where('nama', 'like', '%' . $search . '%');
              });
        });
    }

    /**
     * Scope filter berdasarkan kategori
     */
    public function scopeByKategori(Builder $query, $kategoriId): void
    {
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
    }

    /**
     * Scope filter berdasarkan kondisi
     */
    public function scopeByKondisi(Builder $query, $kondisi): void
    {
        if ($kondisi) {
            $query->where('kondisi', $kondisi);
        }
    }
}