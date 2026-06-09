<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranItem extends Model
{
    protected $fillable = ['setoran_id', 'price_id', 'trash_id', 'berat'];

    public function setoran()
    {
        return $this->belongsTo(Setoran::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    public function trash()
    {
        return $this->belongsTo(Trash::class);
    }
}
