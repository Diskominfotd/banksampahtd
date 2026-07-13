<?php
namespace App\Services\Impl;

use App\Models\BukuTabungan;
use App\Models\Gudang;
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

    private function hitungPersentase(float $today, float $yesterday): float
    {
        if ($yesterday > 0) {
            return round((($today - $yesterday) / $yesterday) * 100, 2);
        }
        if ($today > 0) {
            return 100;
        }
        return 0;
    }

    public function createSetoran(User $nasabah, array $cart, int $bankId): Setoran
    {
        return DB::transaction(function () use ($nasabah, $cart, $bankId) {
            try {
                $totalSaldoSetoran = collect($cart)->sum(fn($c) => $c['harga'] * $c['berat']);

                $bukutabungan = BukuTabungan::where('user_id', $nasabah['id'])
                ->where('bank_id', $bankId)->lockForUpdate()->firstOrFail();
                $setoran = Setoran::create([
                    'penyetor_id' => $nasabah['id'],
                    'total_berat' => collect($cart)->sum('berat'),
                    'total_saldo' => $totalSaldoSetoran,
                    'tanggal' => now(),
                    'buku_tabungan_id' => $bukutabungan->id,
                    'admin_id' => Auth::user()->id,
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
                // $gudang = Gudang::where('bank_id', $bankId)->first();
                // $gudang->increment('berat', collect($cart)->sum('berat'));
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
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;
        if (!$parent) {
            return Setoran::with(['penyetor', 'bukutabungan.bank', 'items']);
        }
        return Setoran::with(['penyetor', 'bukutabungan.bank', 'items'])
        ->whereHas('bukutabungan', function ($q) use ($unitId) {
            $q->where('bank_id', $unitId);
        });
    }

    public function getSetoranByIdNasabah(int $setoranId)
    {
        return Setoran::with(['penyetor', 'admin', 'items.trash'])
            ->where('id', $setoranId)
            ->first();
    }

    public function totalBeratSetoran()
    {
        $total = $this->getSetoranByUnit()->sum('total_berat');
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->getSetoranByUnit()->whereDate('created_at', $todayDate)->sum('total_berat');
        $yesterday = $this->getSetoranByUnit()->whereDate('created_at', $yesterdayDate)->sum('total_berat');

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function totalSaldoSetoran()
    {
        $total = $this->getSetoranByUnit()->sum('total_saldo');
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->getSetoranByUnit()->whereDate('created_at', $todayDate)->sum('total_saldo');
        $yesterday = $this->getSetoranByUnit()->whereDate('created_at', $yesterdayDate)->sum('total_saldo');

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function setoranToday()
    {
        return $this->getSetoranByUnit()
            ->whereDate('created_at', now()->startOfDay())
            ->latest()
            ->limit(5)
            ->get();
    }

    public function setoranByAuthUser()
    {
        $user = $this->checkUser();
        return Setoran::where('penyetor_id', $user->id);
    }

    public function totalSaldoSetoranNasbah()
    {
        $total = $this->setoranByAuthUser()->sum('total_saldo');
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->setoranByAuthUser()
        ->whereDate('created_at', $todayDate)->sum('total_saldo');
        $yesterday = $this->setoranByAuthUser()
        ->whereDate('created_at', $yesterdayDate)->sum('total_saldo');

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function getSetoranByAuthUser()
    {
        return Setoran::with(['bukutabungan.bank', 'penyetor'])
        ->where('penyetor_id', $this->checkUser()->id);
    }
    public function getSetoranByUserByLimit()
    {
        return Setoran::with(['bukutabungan.bank', 'penyetor'])
            ->where('penyetor_id', $this->checkUser()->id)
            ->whereDate('created_at', today())
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getSetoranByUniLimit()
    {
        $auth = $this->checkUser();
        $unitId = $auth->unit->id;
        return Setoran::with(['bukutabungan.bank', 'penyetor'])
            ->whereHas('bukutabungan', function ($q) use ($unitId) {
                $q->where('bank_id', $unitId);
            })
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getGudangByUnit()
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;
        if (!$parent) {
            Gudang::query();
        }
        return Gudang::where('bank_id', $unitId);
    }
    public function totalStokGudang()
    {
        $total = $this->getGudangByUnit()->sum('berat');
        return [
            'total' => $total,
        ];
    }
}
