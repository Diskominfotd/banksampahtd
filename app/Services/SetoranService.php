<?php
namespace App\Services;

use App\Models\Setoran;
use App\Models\Trash;
use App\Models\User;

interface SetoranService
{
    public function createSetoran(User $nasabah, array $cart, int $bankId): Setoran;
    public function getSetoranByUnit();
    public function getSetoranByIdNasabah(int $setoranId);
    public function totalBeratSetoran();
    public function setoranToday();
    public function totalSaldoSetoranNasbah();
    public function getSetoranByAuthUser();
    public function getSetoranByUserByLimit();
    public function getSetoranByUniLimit();
    public function totalStokGudang();
    public function getGudangByUnit();
}
