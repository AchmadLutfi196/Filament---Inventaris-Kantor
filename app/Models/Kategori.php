<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];

    /**
     * Relasi dengan Barang
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    /**
     * Accessor untuk menampilkan nama lengkap kategori
     */
    public function getNamaLengkapAttribute(): string
    {
        return $this->kode . ' - ' . $this->nama;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('kode', 'like', '%' . $search . '%');
    }
}