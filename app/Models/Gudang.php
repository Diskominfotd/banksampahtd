<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'berat', 'saldo', 'bank_id'])]
class Gudang extends Model
{
    protected $table = 'gudangs';
    public function unit()
    {
        return $this->hasMany(BankSampah::class, 'bank_id');
    }
}
