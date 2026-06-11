<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    protected $fillable = ['penyetor_id', 'total_berat', 'tanggal','total_saldo'];

    public function penyetor()
    {
        return $this->belongsTo(User::class, 'penyetor_id');
    }

    public function bank()
    {
        return $this->belongsTo(BankSampah::class, 'bank_id');
    }

    public function items()
    {
        return $this->hasMany(SetoranItem::class);
    }
}
