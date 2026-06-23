<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'total_penarikan', 'sisa_saldo', 'tanggal_transaksi', 
        'owner_id', 'admin_id', 'buku_tabungan_id', 'kode'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];
    public function bukutabungan()
    {
        return $this->belongsTo(BukuTabungan::class, 'buku_tabungan_id');
    }
    protected static function booted()
    {
        static::creating(function ($transaksi) {
            if (empty($transaksi->kode)) {
                $transaksi->kode = 'TRX-' . now()->format('Y-m-d') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
