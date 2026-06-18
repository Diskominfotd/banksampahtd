<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['total_penarikan', 'sisa_saldo', 'tanggal_transaksi', 'owner_id', 'admin_id'];

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
}
