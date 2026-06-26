<?php
namespace App\Services\Impl;

use App\Models\Category;
use App\Models\Setoran;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\LaporanService;
use Illuminate\Support\Facades\Auth;

class LaporanServiceImpl implements LaporanService
{
    public function checkUser()
    {
        return Auth::user();
    }
    public function totalSampahPerBulan()
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;
        if (!$parent) {
            $builder = Setoran::with('bukutabungan')->whereYear('created_at', now()->year);
        } else {
            $builder = Setoran::with('bukutabungan')
                ->whereYear('created_at', now()->year)
                ->whereHas('bukutabungan', function ($q) use ($unitId) {
                    $q->where('bank_id', $unitId);
                });
        }
        $bulanLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $getting = $builder->get();
        $data = $getting->groupBy(fn($row) => (int) $row->created_at->format('n'));
        return collect(range(1, 12))->map(
            fn($bulan) => [
                'bulan' => $bulanLabel[$bulan - 1],
                'total_berat' => (float) ($data->get($bulan)?->sum('total_berat') ?? 0),
                'total_saldo' => (float) ($data->get($bulan)?->sum('total_saldo') ?? 0),
            ],
        );
    }

    public function ringkasanTotalTahunIni()
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;

        $builderStr = Setoran::whereYear('created_at', now()->year);
        $builderTrx = Transaksi::whereYear('created_at', now()->year);
        if ($parent) {
            $builderStr->whereHas('bukutabungan', fn($q) => $q->where('bank_id', $unitId));
            $builderTrx->whereHas('bukutabungan', fn($q) => $q->where('bank_id', $unitId));
        }

        return [
            'total_berat' => (float) $builderStr->sum('total_berat'),
            'total_penarikan' => (float) $builderTrx->sum('total_penarikan'),
        ];
    }

    public function topFiveNasabah()
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;

        $builder = User::withCount('setorans')
        ->withSum('setorans', 'total_berat')
        ->withSum('setorans', 'total_saldo');

        if ($parent) {
            $builder->whereHas('bukutabungans', fn($q) => $q->where('bank_id', $unitId));
        }
        return $builder->having('setorans_sum_total_berat', '>', 0)
        ->orderByDesc('setorans_sum_total_berat')->limit(5)->get();
    }

    public function komposisiSampahBulanIni()
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;

        $withFilter = fn($bulan, $tahun) => ['trash.setoranItems' => fn($q) => $q->whereHas('setoran', fn($q) => $q->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->when($parent, fn($q) => $q->whereHas('bukutabungan', fn($q) => $q->where('bank_id', $unitId))))];

        $bulanIni = Category::with($withFilter(now()->month, now()->year))
            ->get()
            ->mapWithKeys(
                fn($cat) => [
                    $cat->id => (float) $cat->trash->flatMap->setoranItems->sum('berat'),
                ],
            );

        $bulanLalu = Category::with($withFilter(now()->subMonth()->month, now()->subMonth()->year))
            ->get()
            ->mapWithKeys(
                fn($cat) => [
                    $cat->id => (float) $cat->trash->flatMap->setoranItems->sum('berat'),
                ],
            );

        return Category::all()->map(
            fn($cat) => [
                'nama' => $cat->name,
                'total' => $bulanIni[$cat->id] ?? 0,
                'persen' => ($bulanLalu[$cat->id] ?? 0) > 0 ? round((($bulanIni[$cat->id] - $bulanLalu[$cat->id]) / $bulanLalu[$cat->id]) * 100, 1) : (($bulanIni[$cat->id] ?? 0) > 0 ? 100 : 0),
            ],
        );
    }
}
