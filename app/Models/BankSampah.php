<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'parent_id', 'alamat', 'telepon', 'use_parent_price'])]
class BankSampah extends Model
{
    protected $table = 'bank_sampahs';

    public function parent()
    {
        return $this->belongsTo(BankSampah::class, 'parent_id');
    }

    public function units()
    {
        return $this->hasMany(BankSampah::class, 'parent_id');
    }
}
