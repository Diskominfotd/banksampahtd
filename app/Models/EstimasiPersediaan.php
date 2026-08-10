<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimasiPersediaan extends Model
{
    protected $fillable = [
        'bank_id',
        'nilai',
        'keterangan',
    ];
}