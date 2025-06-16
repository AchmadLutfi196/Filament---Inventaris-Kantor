<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    
    protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'barang_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'keperluan',
        'catatan_admin',
        'total_biaya_sewa',
        'total_deposit',
        'denda_keterlambatan',
        'total_pembayaran',
        'payment_status',
        'midtrans_order_id',
        'midtrans_response',
        'paid_at'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'jumlah' => 'integer',
        'total_biaya_sewa' => 'decimal:2',
        'total_deposit' => 'decimal:2',
        'denda_keterlambatan' => 'decimal:2',
        'total_pembayaran' => 'decimal:2',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_DIPINJAM = 'dipinjam';
    const STATUS_DIKEMBALIKAN = 'dikembalikan';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function getJumlahHariAttribute(): int
    {
        if (!$this->tanggal_pinjam || !$this->tanggal_kembali_rencana) {
            return 0;
        }
        
        return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali_rencana) + 1;
    }

    public function getTerlambatAttribute(): bool
    {
        if ($this->status !== self::STATUS_DIPINJAM) {
            return false;
        }
        
        return $this->tanggal_kembali_rencana < Carbon::now()->startOfDay();
    }

    public function getHariTerlambatAttribute(): int
    {
        if (!$this->terlambat) {
            return 0;
        }
        
        return $this->tanggal_kembali_rencana->diffInDays(Carbon::now()->startOfDay());
    }

    public function hitungTotalBiayaSewa(): float
    {
        if (!$this->barang) {
            return 0;
        }
        
        $jumlahHari = $this->jumlah_hari;
        $hargaPerHari = $this->barang->harga_sewa_per_hari ?? 0;
        
        return $jumlahHari * $hargaPerHari * $this->jumlah;
    }

    public function hitungTotalDeposit(): float
    {
        if (!$this->barang) {
            return 0;
        }
        
        return ($this->barang->biaya_deposit ?? 0) * $this->jumlah;
    }

    public function hitungDendaKeterlambatan(): float
    {
        if (!$this->terlambat) {
            return 0;
        }
        
        $hariTerlambat = $this->hari_terlambat;
        $totalBiayaSewa = $this->total_biaya_sewa ?: $this->hitungTotalBiayaSewa();
        
        return $totalBiayaSewa * 0.05 * $hariTerlambat;
    }

    public function hitungTotalPembayaran(): float
    {
        $totalSewa = $this->hitungTotalBiayaSewa();
        $totalDeposit = $this->hitungTotalDeposit();
        $denda = $this->hitungDendaKeterlambatan();
        
        return $totalSewa + $totalDeposit + $denda;
    }

    public function updateBiaya(): void
    {
        if (!$this->relationLoaded('barang')) {
            $this->load('barang');
        }

        $totalSewa = $this->hitungTotalBiayaSewa();
        $totalDeposit = $this->hitungTotalDeposit();
        $denda = $this->hitungDendaKeterlambatan();
        $totalPembayaran = $totalSewa + $totalDeposit + $denda;

        $this->update([
            'total_biaya_sewa' => $totalSewa,
            'total_deposit' => $totalDeposit,
            'denda_keterlambatan' => $denda,
            'total_pembayaran' => $totalPembayaran
        ]);
    }

    // PERBAIKI ACCESSOR INI - Gunakan nilai database, bukan perhitungan ulang
    public function getFormattedTotalPembayaranAttribute(): string
    {
        $total = $this->attributes['total_pembayaran'] ?? 0; // Gunakan nilai database langsung
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getFormattedTotalBiayaSewaAttribute(): string
    {
        $total = $this->attributes['total_biaya_sewa'] ?? 0; // Gunakan nilai database langsung
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getFormattedTotalDepositAttribute(): string
    {
        $total = $this->attributes['total_deposit'] ?? 0; // Gunakan nilai database langsung
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getFormattedDendaKeterlambatanAttribute(): string
    {
        $total = $this->attributes['denda_keterlambatan'] ?? 0; // Gunakan nilai database langsung
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function scopeTerlambat(Builder $query): void
    {
        $query->where('status', self::STATUS_DIPINJAM)
              ->where('tanggal_kembali_rencana', '<', Carbon::now());
    }

    public static function generateKodePeminjaman(): string
    {
        $tanggal = Carbon::now()->format('Ymd');
        $count = self::whereDate('created_at', Carbon::today())->count() + 1;
        
        return 'PJM-' . $tanggal . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($peminjaman) {
            if (empty($peminjaman->kode_peminjaman)) {
                $peminjaman->kode_peminjaman = self::generateKodePeminjaman();
            }
        });

        static::created(function ($peminjaman) {
            \Log::info('Peminjaman created, updating biaya', ['id' => $peminjaman->id]);
            
            $peminjaman->refresh();
            $peminjaman->updateBiaya();
        });
    }
}