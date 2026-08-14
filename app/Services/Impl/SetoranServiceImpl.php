<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\BukuTabungan;
use App\Models\Gudang;
use App\Models\Pengeluaran;
use App\Models\Setoran;
use App\Models\Transaksi;
use App\Models\TransaksiBongkarGudang;
use App\Models\User;
use App\Services\SetoranService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetoranServiceImpl implements SetoranService
{
    public function getTransaksis()
    {
        $user = $this->checkUser();
        $parent = $user->unit->parent_id;
        $unitId = $user->unit->id;
        if (!$parent) {
            return Transaksi::with(['owner', 'bukutabungan.bank']);
        } else {
            return Transaksi::with(['owner', 'bukutabungan.bank'])->whereHas('bukutabungan', function ($q) use ($unitId) {
                $q->where('bank_id', $unitId);
            });
        }
    }

    private function getJakartaDayRange(int $daysAgo = 0): array
    {
        $start = now('Asia/Jakarta')->subDays($daysAgo)->startOfDay()->timezone('UTC');
        $end = now('Asia/Jakarta')
            ->subDays($daysAgo - 1)
            ->startOfDay()
            ->timezone('UTC');

        return [$start, $end];
    }
    public function checkUser()
    {
        return Auth::user();
    }

    public function createSetoran(User $nasabah, array $cart, int $bankId): Setoran
    {
        return DB::transaction(function () use ($nasabah, $cart, $bankId) {
            try {
                $totalSaldoSetoran = collect($cart)->sum(fn($c) => $c['harga'] * $c['berat']);

                $bukutabungan = BukuTabungan::where('user_id', $nasabah['id'])->where('bank_id', $bankId)->lockForUpdate()->firstOrFail();
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
        return Setoran::with(['penyetor', 'bukutabungan.bank', 'items'])->whereHas('bukutabungan', function ($q) use ($unitId) {
            $q->where('bank_id', $unitId);
        });
    }

    public function getSetoranByIdNasabah(int $setoranId)
    {
        return Setoran::with(['penyetor', 'admin', 'items.trash'])
            ->where('id', $setoranId)
            ->first();
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

    public function getSetoranByAuthUser()
    {
        return Setoran::with(['bukutabungan.bank', 'penyetor'])->where('penyetor_id', $this->checkUser()->id);
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
    public function totalBeratSetoran()
    {
        $total = $this->getSetoranByUnit()->sum('total_berat');

        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_berat');

        $yesterday = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_berat');
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function totalSaldoSetoran()
    {
        $total = $this->getSetoranByUnit()->sum('total_saldo');

        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_saldo');
        $yesterday = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_saldo');
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function totalSaldoSetoranNasbah()
    {
        $total = $this->setoranByAuthUser()->sum('total_saldo');
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->setoranByAuthUser()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_saldo');

        $yesterday = $this->setoranByAuthUser()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_saldo');
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function totalSaldoTabunganNasbahTersisa()
    {
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $totalPenarikan = Transaksi::whereHas('bukutabungan', function ($q) {
            $q->where('bank_id', $this->checkUser()->unit->id);
        })->sum('total_penarikan');

        $totalSetoran = $this->getSetoranByUnit()->sum('total_saldo');

        $total = $totalSetoran - $totalPenarikan;

        $todaySetoran = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_saldo');

        $todayPenarikan = Transaksi::whereHas('bukutabungan', function ($q) {
            $q->where('bank_id', $this->checkUser()->unit->id);
        })
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_penarikan');

        $today = $todaySetoran - $todayPenarikan;

        $yesterdaySetoran = $this->getSetoranByUnit()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_saldo');

        $yesterdayPenarikan = Transaksi::whereHas('bukutabungan', function ($q) {
            $q->where('bank_id', $this->checkUser()->unit->id);
        })
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_penarikan');

        $yesterday = $yesterdaySetoran - $yesterdayPenarikan;
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function saldoBersih()
    {
        $pendapatan = $this->pendapatanBersih();
        $setoran = $this->totalSaldoTabunganNasbahTersisa();

        $total = $pendapatan['total'] - $setoran['total'];
        $today = $pendapatan['today'] - $setoran['today'];
        $yesterday = $pendapatan['yesterday'] - $setoran['yesterday'];
        $selisih = $today - $yesterday;

        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function pendapatanBersih()
    {
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);
        $auth = $this->checkUser();
        $parent = $auth->unit->parent_id;
        $unitId = $auth->unit->id;
        $pendapatan = TransaksiBongkarGudang::with('gudang');
        $pengeluaran = Pengeluaran::with('gudang');
        if ($parent) {
            $pendapatan->whereHas('gudang', function ($q) use ($unitId) {
                $q->where('bank_id', $unitId);
            });
            $pengeluaran->whereHas('gudang', function ($q) use ($unitId) {
                $q->where('bank_id', $unitId);
            });
        }
        $total = $pendapatan->sum('total_penarikan') - $pengeluaran->sum('total_penarikan');
        $today = TransaksiBongkarGudang::whereBetween('created_at', [$todayStart, $todayEnd])->sum('total_penarikan');
        $yesterday = Pengeluaran::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('total_penarikan');
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function editSetoran(int $setoranId, array $data)
    {
        return DB::transaction(function () use ($setoranId, $data) {
            $setoran = Setoran::with(['items.trash'])
                ->where('id', $setoranId)
                ->lockForUpdate()
                ->first();

            if (!$setoran) {
                throw new \Exception('Data setoran tidak ditemukan.');
            }

            foreach ($data['items'] as $itemInput) {
                if (!isset($itemInput['id'], $itemInput['berat'])) {
                    continue;
                }

                $item = $setoran->items->firstWhere('id', $itemInput['id']);
                if (!$item) {
                    continue;
                }

                $berat = (float) $itemInput['berat'];
                if ($berat <= 0) {
                    continue;
                }

                $item->update([
                    'berat' => $berat,
                    'sub_total' => $berat * $item->harga,
                ]);
            }

            $setoran->refresh();
            $totalBerat = $setoran->items->sum('berat');
            $totalSaldo = $setoran->items->sum('sub_total');

            $setoran->update([
                'total_berat' => $totalBerat,
                'total_saldo' => $totalSaldo,
            ]);

            $this->recalcBukuTabungan($setoran->buku_tabungan_id);

            $setoran->fresh(['items.trash']);
            return session()->flash('success', 'Perubahan Berhasil');
        });
    }

    private function recalcBukuTabungan(int $bukuTabunganId): float
    {
        $penarikan = Transaksi::where('buku_tabungan_id', $bukuTabunganId)->get()->map(
            fn($t) => (object) [
                'id' => $t->id,
                'tanggal' => $t->tanggal_transaksi,
                'jumlah' => -$t->total_penarikan,
                'tipe' => 'penarikan',
            ],
        );

        $setoran = Setoran::where('buku_tabungan_id', $bukuTabunganId)->get()->map(
            fn($s) => (object) [
                'id' => $s->id,
                'tanggal' => $s->tanggal,
                'jumlah' => $s->total_saldo,
                'tipe' => 'setoran',
            ],
        );

        $ledger = $penarikan->concat($setoran)->sortBy([['tanggal', 'asc'], ['id', 'asc']]);

        $saldoBerjalan = 0;
        foreach ($ledger as $entry) {
            $saldoBerjalan += $entry->jumlah;
            if ($entry->tipe === 'penarikan') {
                Transaksi::where('id', $entry->id)->update(['sisa_saldo' => $saldoBerjalan]);
            }
        }

        BukuTabungan::where('id', $bukuTabunganId)->update(['saldo' => $saldoBerjalan]);

        return $saldoBerjalan;
    }

    public function deleteSetoran(int $setoranId)
    {
        return DB::transaction(function () use ($setoranId) {
            $setoran = Setoran::with(['items.trash'])
                ->where('id', $setoranId)
                ->lockForUpdate()
                ->first();

            if (!$setoran) {
                throw new \Exception('Data setoran tidak ditemukan.');
            }

            $bukuTabunganId = $setoran->buku_tabungan_id;

            $setoran->items()->delete();
            $setoran->delete();

            $this->recalcBukuTabungan($bukuTabunganId);

            session()->flash('success', 'Berhasil di hapus');
        });
    }
    public function getBankUnit()
    {
        return BankSampah::with('gudang');
    }

    public function totalSetoranToday()
    {
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        $setoran = $this->getSetoranByUnit()->whereBetween('created_at', [$todayStart, $todayEnd]);
        $totalBerat = $setoran->sum('total_berat');
        $totalSetoran = $setoran->count('id');
        return [
            'berat' => $totalBerat,
            'total' => $totalSetoran,
        ];
    }
    public function totalPenarikanSaldoNasabah()
    {
        $total = $this->getTransaksis()->sum('total_penarikan');
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->getTransaksis()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_penarikan');

        $yesterday = $this->getTransaksis()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_penarikan');
        $selisih = $today - $yesterday;
        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function estimasiSisaStokSampah()
    {
        $totalSetoran = $this->totalSaldoSetoran();
        $penarikan = $this->totalPenarikanSaldoNasabah();

        $total = $totalSetoran['total'] - $penarikan['total'];
        $today = $totalSetoran['today'] - $penarikan['today'];

        $yesterday = $totalSetoran['yesterday'] - $penarikan['yesterday'];
        $selisih = $today - $yesterday;

        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
    public function estimasiKuntungan()
    {
        $totalKeuntunganKas = $this->saldoBersih();
        $estimasiSisaStokGudang = $this->estimasiSisaStokSampah();

        $total = $totalKeuntunganKas['total'] + $estimasiSisaStokGudang['total'];
        $today = $totalKeuntunganKas['today'] + $estimasiSisaStokGudang['today'];
        $yesterday = $totalKeuntunganKas['yesterday'] + $estimasiSisaStokGudang['yesterday'];
        
        $selisih = $today - $yesterday;

        return [
            'total' => $total,
            'today' => $today,
            'selisih' => $selisih,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }
}
