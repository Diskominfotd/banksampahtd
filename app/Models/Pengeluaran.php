<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['kode', 'total_penarikan', 'admin_id', 'gudang_id', 'keterangan'])]
class Pengeluaran extends Model
{
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class);
    }
    public function bukutabungan()
    {
        return $this->belongsTo(BukuTabungan::class, 'buku_tabungan_id');
    }
    protected static function booted()
    {
        static::creating(function ($pengeluaran) {
            if (empty($pengeluaran->kode)) {
                $pengeluaran->kode = static::generateUniqueKode();
            }
        });
    }
    protected static function generateUniqueKode(): string
    {
        do {
            $kode = 'PRN-' . now()->format('Y-m-d') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }
}
