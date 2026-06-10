<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTabungan extends Model
{
    protected $fillable = [
    'nama','nomor_rekening', 'saldo', 'bank_id', 'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function banks()
    {
        return $this->belongsToMany(BankSampah::class);
    }
}
