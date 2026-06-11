<?php
namespace App\Services\Impl;
use App\Models\Setoran;
use App\Models\User;
use App\Services\SetoranService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetoranServiceImpl implements SetoranService
{
    public function checkUser()
    {
        return Auth::user();
    }
    public function createSetoran(User $nasabah, array $cart): Setoran
    {
        return DB::transaction(function () use ($nasabah, $cart) {
            $setoran = Setoran::create([
                'penyetor_id' => $nasabah['id'],
                'total_berat' => collect($cart)->sum('berat'),
                'total_saldo' => collect($cart)->sum(fn($c) => $c['harga'] * $c['berat']),
                'tanggal' => now(),
            ]);

            foreach ($cart as $item) {
                $setoran->items()->create([
                    'price_id' => $item['price_id'],
                    'trash_id' => $item['trash_id'],
                    'type' => $item['type'],
                    'berat' => $item['berat'],
                    'harga' => $item['harga'],
                    'sub_total' => $item['harga'] * $item['berat'],
                ]);
            }

            return $setoran;
        });
    }

    public function getSetoranByUnit()
    {
        $auth = $this->checkUser();
        $unitId = $auth->unit->id; // <-- simpan sekali, pakai di mana-mana
        return Setoran::with([
            'penyetor',
            'penyetor.bukutabungans' => function ($q) use ($unitId) {
                $q->where('bank_id', $unitId)->with([
                    'bank' => function ($q2) use ($unitId) {
                        $q2->where('id', $unitId); // filter bank sesuai unit
                    },
                ]);
            },
            'items',
        ])->whereHas('penyetor', function ($q) use ($unitId) {
            $q->whereHas('bukutabungans', function ($q2) use ($unitId) {
                $q2->where('bank_id', $unitId);
            });
        });
    }

    public function getSetoranByIdNasabah(int $nasabahId)
    {
        return Setoran::with(['penyetor', 'items.trash'])
            ->where('penyetor_id', $nasabahId)
            ->first();
    }
}
