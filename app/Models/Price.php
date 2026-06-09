<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['trash_id', 'bank_id', 'harga','type'])]
class Price extends Model
{
    public function trash()
    {
        return $this->belongsTo(Trash::class);
    }
    
    public function bank()
    {
        return $this->belongsTo(BankSampah::class);
    }
}
