<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'syarat', 'category_id'])]
class Trash extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function setoranItems()
    {
        return $this->hasMany(SetoranItem::class);
    }
}
