<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransaksiBongkarGudang extends Model
{
    protected $fillable = ['kode','total_penarikan', 'total_berat','admin_id', 'gudang_id','keterangan'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    protected static function booted()
    {
        static::creating(function ($transaksi) {
            if (empty($transaksi->kode)) {
                $unit = Auth::user()?->unit?->kode_bank ?? 'UNK';
                $transaksi->kode = 'TRX-GDG-' . $unit . '-' . now()->format('Y-m-d') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }
}