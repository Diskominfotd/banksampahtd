<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $fillable = [
    'penyetor_id', 'total_berat', 'tanggal', 'total_saldo', 'buku_tabungan_id', 'kode', 'admin_id'
    ];
    public function penyetor()
    {
        return $this->belongsTo(User::class, 'penyetor_id');
    }
    public function admin()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(SetoranItem::class);
    }
    public function bukutabungan()
    {
        return $this->belongsTo(BukuTabungan::class, 'buku_tabungan_id');
    }
    protected static function booted()
    {
        static::creating(function ($setoran) {
            if (empty($setoran->kode)) {
                $setoran->kode = 'STR-' . now()->format('Y-m-d') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
