<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('nama')]
class Organisasi extends Model
{
    public function user()
    {
        return $this->hasMany(User::class, 'organisasi_id');
    }
}
