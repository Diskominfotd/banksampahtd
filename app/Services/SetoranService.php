<?php
namespace App\Services;

use App\Models\Setoran;
use App\Models\Trash;
use App\Models\User;

interface SetoranService
{
    public function createSetoran(User $nasabah, array $cart): Setoran;
}
