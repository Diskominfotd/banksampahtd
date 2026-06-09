<?php
namespace App\Services\Impl;
use App\Models\Setoran;
use App\Models\User;
use App\Services\SetoranService;
use Illuminate\Support\Facades\DB;

class SetoranServiceImpl implements SetoranService
{
    public function createSetoran(User $nasabah, array $cart): Setoran
    {
        return DB::transaction(function () use ($nasabah, $cart) {

            $setoran = Setoran::create([
                'penyetor_id' => $nasabah['id'],
                'total_berat' => collect($cart)->sum('berat'),
                'tanggal' => now(),
            ]);

            foreach ($cart as $item) {
                $setoran->items()->create([
                    'price_id' => $item['price_id'],
                    'trash_id' => $item['trash_id'],
                    'berat' => $item['berat'],
                ]);
            }

            return $setoran;
        });
    }
}