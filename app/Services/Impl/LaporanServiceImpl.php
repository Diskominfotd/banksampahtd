<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\Category;
use App\Models\Setoran;
use App\Models\SetoranItem;
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
    public function totalSampahPerBulan(string $tahun)
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;
        if (!$parent) {
            $builder = Setoran::with('bukutabungan')->whereYear('created_at', $tahun);
        } else {
            $builder = Setoran::with('bukutabungan')
                ->whereYear('created_at', $tahun)
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
                'total_berat' => round($data->get($bulan)?->sum('total_berat') ?? 0, 2),
                'total_saldo' => round($data->get($bulan)?->sum('total_saldo') ?? 0, 2),
            ],
        );
    }

    public function ringkasanTotalTahunIni(string $tahun)
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;

        $builderStr = Setoran::whereYear('created_at', $tahun);
        $builderTrx = Transaksi::whereYear('created_at', $tahun);
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
        $unit = $auth->unit;
        $unitId = $unit->id;
        $parent = $unit->parent_id;
        $topNasabahQuery = User::query();
        if ($parent) {
            $topNasabahQuery
                ->withCount([
                    'setorans as setorans_count' => function ($q) use ($unitId) {
                        $q->whereHas('bukutabungan', fn($q2) => $q2->where('bank_id', $unitId));
                    },
                ])
                ->withSum(
                    [
                        'setorans as setorans_sum_total_berat' => function ($q) use ($unitId) {
                            $q->whereHas('bukutabungan', fn($q2) => $q2->where('bank_id', $unitId));
                        },
                    ],
                    'total_berat',
                )
                ->withSum(
                    [
                        'setorans as setorans_sum_total_saldo' => function ($q) use ($unitId) {
                            $q->whereHas('bukutabungan', fn($q2) => $q2->where('bank_id', $unitId));
                        },
                    ],
                    'total_saldo',
                )->whereHas('bukutabungans', fn($q) => $q->where('bank_id', $unitId));
                }
             else {
            $topNasabahQuery
                ->withCount('setorans as setorans_count')
                ->withSum('setorans as setorans_sum_total_berat', 'total_berat')
                ->withSum('setorans as setorans_sum_total_saldo', 'total_saldo')
                ->whereHas('bukutabungans');
        }

        $topNasabah = $topNasabahQuery
            ->having('setorans_sum_total_berat', '>', 0)
            ->orderByDesc('setorans_sum_total_berat')
            ->limit(5)
            ->get();

        return [
            'top_nasabah' => $topNasabah,
            'unit' => $unit,
        ];
    }
    public function komposisiSampahBulanIni(string $tahun, string $bulan)
    {
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;

        $withFilter = fn($bulan, $tahun) => ['trash.setoranItems' => fn($q) => $q->whereHas('setoran', fn($q) => $q->when($bulan, fn($q) => $q->whereMonth('created_at', $bulan))->whereYear('created_at', $tahun)->when($parent, fn($q) => $q->whereHas('bukutabungan', fn($q) => $q->where('bank_id', $unitId))))];

        if ($bulan) {
            $tanggalLalu = \Carbon\Carbon::create($tahun, $bulan, 1)->subMonth();
            $bulanLaluNum = $tanggalLalu->month;
            $tahunLaluNum = $tanggalLalu->year;
        } else {
            $bulanLaluNum = null;
            $tahunLaluNum = $tahun - 1;
        }

        $bulanIni = Category::with($withFilter($bulan, $tahun))->get()->mapWithKeys(
            fn($cat) => [
                $cat->id => (float) $cat->trash->flatMap->setoranItems->sum('berat'),
            ],
        );

        $bulanLalu = Category::with($withFilter($bulanLaluNum, $tahunLaluNum))->get()->mapWithKeys(
            fn($cat) => [
                $cat->id => (float) $cat->trash->flatMap->setoranItems->sum('berat'),
            ],
        );
        $tahuns = Setoran::orderByDesc('created_at')->pluck('created_at')->map(fn($d) => $d->year)->unique()->values();
        $komposisi = Category::all()->map(
            fn($cat) => [
                'nama' => $cat->name,
                'total' => $bulanIni[$cat->id] ?? 0,
                'persen' => ($bulanLalu[$cat->id] ?? 0) > 0 ? round((($bulanIni[$cat->id] - $bulanLalu[$cat->id]) / $bulanLalu[$cat->id]) * 100, 1) : (($bulanIni[$cat->id] ?? 0) > 0 ? 100 : 0),
            ],
        );
        return [
            'kategori' => $komposisi,
            'total_bulan_ini' => $bulanIni->sum(),
            'total_bulan_lalu' => $bulanLalu->sum(),
            'tahuns' => $tahuns,
        ];
    }
}
