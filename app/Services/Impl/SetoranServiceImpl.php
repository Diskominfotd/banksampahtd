<?php
namespace App\Services\Impl;

use App\Models\BukuTabungan;
use App\Models\Setoran;
use App\Models\User;
use App\Services\SetoranService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetoranServiceImpl implements SetoranService
{
    public function checkUser()
    {
        return Auth::user();
    }
    public function createSetoran(User $nasabah, array $cart, int $bankId): Setoran
    {
        return DB::transaction(function () use ($nasabah, $cart, $bankId) {
            try {
                $totalSaldoSetoran = collect($cart)->sum(fn($c) => $c['harga'] * $c['berat']);

                // ✅ filter berdasarkan bank_id yang sesuai
                $bukutabungan = BukuTabungan::where('user_id', $nasabah['id'])->where('bank_id', $bankId)->lockForUpdate()->firstOrFail();

                $setoran = Setoran::create([
                    'penyetor_id' => $nasabah['id'],
                    'total_berat' => collect($cart)->sum('berat'),
                    'total_saldo' => $totalSaldoSetoran,
                    'tanggal' => now(),
                    'buku_tabungan_id' => $bukutabungan->id,
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

                $bukutabungan->increment('saldo', $totalSaldoSetoran);
                return $setoran;
            } catch (\Throwable $e) {
                Log::error('createSetoran failed', [
                    'nasabah_id' => $nasabah['id'],
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }

    public function getSetoranByUnit()
    {
        $auth = $this->checkUser();
        $unitId = $auth->unit->id;

        return Setoran::with(['penyetor', 'bukutabungan.bank', 'items'])->whereHas('bukutabungan', function ($q) use ($unitId) {
            $q->where('bank_id', $unitId);
        });
    }
    public function getSetoranByIdNasabah(int $nasabahId)
    {
        return Setoran::with(['penyetor', 'items.trash'])
            ->where('penyetor_id', $nasabahId)
            ->first();
    }

    public function totalBeratSetoran()
    {
        $today = $this->getSetoranByUnit()->whereDate('created_at', today())->sum('total_berat');

        $yesterday = $this->getSetoranByUnit()
            ->whereDate('created_at', today()->subDay())
            ->sum('total_berat');

        if ($yesterday > 0) {
            $persentase = round((($today - $yesterday) / $yesterday) * 100, 2);
        } elseif ($today > 0) {
            $persentase = 100;
        } else {
            $persentase = 0;
        }

        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $persentase,
        ];
    }
    public function totalSaldoSetoran()
    {
        $today = $this->getSetoranByUnit()->whereDate('created_at', today())->sum('total_saldo');

        $yesterday = $this->getSetoranByUnit()
            ->whereDate('created_at', today()->subDay())
            ->sum('total_saldo');

        if ($yesterday > 0) {
            $persentase = round((($today - $yesterday) / $yesterday) * 100, 2);
        } elseif ($today > 0) {
            $persentase = 100;
        } else {
            $persentase = 0;
        }

        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $persentase,
        ];
    }
    public function setoranToday()
    {
        return $this->getSetoranByUnit()->whereDate('created_at', today())->limit(5)->get();
    }

    public function setoranByAuthUser()
    {
        $user = $this->checkUser();
        return Setoran::where('penyetor_id', $user->id);
    }

    public function totalSaldoSetoranNasbah()
    {
        $today = $this->setoranByAuthUser()->whereDate('created_at', today())->sum('total_saldo');
        $yesterday = $this->setoranByAuthUser()
            ->whereDate('created_at', today()->subDay())
            ->sum('total_saldo');
        if ($yesterday > 0) {
            $persentase = round((($today - $yesterday) / $yesterday) * 100, 2);
        } elseif ($today > 0) {
            $persentase = 100;
        } else {
            $persentase = 0;
        }
        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $persentase,
        ];
    }
}
