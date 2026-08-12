<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\BukuTabungan;
use App\Models\Gudang;
use App\Models\Pengeluaran;
use App\Models\Setoran;
use App\Models\Transaksi;
use App\Models\TransaksiBongkarGudang;
use App\Services\TransaksiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiServiceImpl implements TransaksiService
{
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

    public function reduceSaldo(string $rekening, float $jumlah)
    {
        return DB::transaction(function () use ($rekening, $jumlah) {
            BukuTabungan::where('nomor_rekening', $rekening)->decrement('saldo', $jumlah);
        });
    }

    public function createTransaksi(array $data)
    {
        return DB::transaction(function () use ($data) {
            $trx = Transaksi::create([
                'total_penarikan' => $data['jumlah'],
                'sisa_saldo' => $data['saldo'] - $data['jumlah'],
                'tanggal_transaksi' => Carbon::now(),
                'owner_id' => $data['user_id'],
                'admin_id' => $this->checkUser()->id,
                'buku_tabungan_id' => $data['buku_tabungan_id'],
            ]);
            $bk = BukuTabungan::where('id', $trx->buku_tabungan_id)->first();
            Pengeluaran::create([
                'total_penarikan' => $trx->total_penarikan,
                'keterangan' => 'Penarikan tabungan oleh nasabah dengan nomor Rekening : ' . $bk->nomor_rekening,
                'admin_id' => $trx->admin_id,
                'gudang_id' => $this->checkUser()->unit->gudang->id,
                'buku_tabungan_id' => $bk->id,
            ]);
            session()->flash('success', 'Berhasil');
        });
    }

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

    public function penarikanToday()
    {
        $todayDate = now()->startOfDay();
        return $this->getTransaksis()->whereDate('created_at', $todayDate)->latest()->limit(5)->get();
    }

    public function transaksiById(int $id)
    {
        return Transaksi::with(['owner', 'admin', 'bukutabungan'])
            ->where('id', $id)
            ->first();
    }

    public function transaksiByAuthUser()
    {
        $auth = $this->checkUser();
        return Transaksi::where('owner_id', $auth->id);
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
    public function totalTransaksiNasabah()
    {
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->transaksiByAuthUser()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_penarikan');

        $yesterday = $this->transaksiByAuthUser()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_penarikan');

        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function totalPendapatan()
    {
        $total = $this->getTrxGudang()->sum('total_penarikan');

        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->getTrxGudang()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_penarikan');

        $yesterday = $this->getTrxGudang()
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

    public function totalPengeluaranByUnit()
    {
        $total = $this->getPengeluaranByGudang()->sum('total_penarikan');
        [$todayStart, $todayEnd] = $this->getJakartaDayRange(0);
        [$yesterdayStart, $yesterdayEnd] = $this->getJakartaDayRange(1);

        $today = $this->getPengeluaranByGudang()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('total_penarikan');

        $yesterday = $this->getPengeluaranByGudang()
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total_penarikan');

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function getTrxByAuthUser()
    {
        return Transaksi::with(['bukutabungan.bank'])->where('owner_id', $this->checkUser()->id);
    }
    public function getTrxByUserByLimit()
    {
        return Transaksi::with(['bukutabungan.bank'])
            ->where('owner_id', $this->checkUser()->id)
            ->whereDate('created_at', today())
            ->latest()
            ->limit(5)
            ->get();
    }
    public function getTrxByUniLimit()
    {
        $auth = $this->checkUser();
        $unitId = $auth->unit->id;
        return Transaksi::with(['bukutabungan.bank', 'penyetor'])
            ->whereHas('bukutabungan', function ($q) use ($unitId) {
                $q->where('bank_id', $unitId);
            })
            ->latest()
            ->limit(5)
            ->get();
    }

    public function bongkarGudang(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->checkUser();
            $userId = $user->id;
            $unitId = $user->unit->id;
            $gdg = Gudang::where('bank_id', $unitId)->first();
            if (!$gdg) {
                session()->flash('error', 'Gudang tidak ditemukan untuk unit ini');
                return;
            }
            TransaksiBongkarGudang::create([
                'total_penarikan' => $data['total_penarikan'],
                'keterangan' => $data['keterangan'],
                'admin_id' => $userId,
                'gudang_id' => $gdg->id,
            ]);
            session()->flash('success', 'Berhasil');
        });
    }
    public function getTrxGudangById(int $id)
    {
        return TransaksiBongkarGudang::with('admin')->findOrFail($id);
    }

    public function getTrxGudang()
    {
        $unit = $this->checkUser()->unit->id;
        $bank = BankSampah::with('gudang')->where('id', $unit)->first();
        return TransaksiBongkarGudang::with('admin')->where('gudang_id', $bank->gudang->id);
    }

    public function getPengeluaran()
    {
        return Pengeluaran::with('admin')->where('gudang_id', $this->checkUser()->unit->gudang->id);
    }

    public function getPengeluaranByGudang()
    {
        $gudang = $this->checkUser()->unit->gudang->id;
        return Pengeluaran::where('gudang_id', $gudang);
    }

    public function buatPengeluaran(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->checkUser();
            Pengeluaran::create([
                'total_penarikan' => $data['total_penarikan'],
                'keterangan' => $data['keterangan'],
                'admin_id' => $user->id,
                'gudang_id' => $user->unit->gudang->id,
            ]);
            session()->flash('success', 'Berhasil');
        });
    }
    public function pengeluaranById(int $id)
    {
        return Pengeluaran::with('admin')->where('id', $id)->first();
    }

    public function trxDetail(int $trxId)
    {
        $transaksi = Transaksi::with('bukutabungan')->where('id', $trxId)->first();
        return $transaksi;
    }

    public function trxEdit(int $trxId, array $data)
    {
        return DB::transaction(function () use ($trxId, $data) {
            $transaksi = Transaksi::where('id', $trxId)->lockForUpdate()->first();

            $transaksi->update([
                'total_penarikan' => $data['total_penarikan'],
            ]);

            $this->recalcBukuTabungan($transaksi->buku_tabungan_id);
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

    public function deleteTrxGudang(int $trxId)
    {
        $trx = TransaksiBongkarGudang::find($trxId);
        if (!$trx) {
            return session()->flash('error', 'Transaksi Tidak Ditemukan');
        }
        $trx->delete();

        return session()->flash('success', 'Berhasil Dihapus');
    }
    public function editTrxGudang(int $trxId, array $data)
    {
        return DB::transaction(function () use ($trxId, $data) {
            $trx = TransaksiBongkarGudang::find($trxId);
            if (!$trx) {
                return session()->flash('error', 'Transaksi Tidak Ditemukan');
            }
            $trx->update([
                'total_penarikan' => $data['nilai'],
                'keterangan' => $data['keterangan'],
            ]);
            return session()->flash('success', 'Perubahan Berhasil');
        });
    }

    public function deleteTrxPengeluaran(int $trxId)
    {
        $trx = Pengeluaran::find($trxId);
        if (!$trx) {
            return session()->flash('error', 'Transaksi Tidak Ditemukan');
        }
        $trx->delete();

        return session()->flash('success', 'Berhasil Dihapus');
    }
    public function editTrxPengeluaran(int $trxId, array $data)
    {
        return DB::transaction(function () use ($trxId, $data) {
            $trx = Pengeluaran::find($trxId);
            if (!$trx) {
                session()->flash('error', 'Transaksi Tidak Ditemukan');
                return;
            }
            $bukuTabunganLama = $trx->buku_tabungan_id;
            $bukuTabunganBaru = $data['buku_tabungan'] ?? null;

            $trx->update([
                'total_penarikan' => $data['nilai'],
                'keterangan' => $data['keterangan'],
                'buku_tabungan_id' => $bukuTabunganBaru,
            ]);
            if ($bukuTabunganLama !== null) {
                $this->recalcBukuTabungan($bukuTabunganLama);
            }
            if ($bukuTabunganBaru !== null && $bukuTabunganBaru !== $bukuTabunganLama) {
                $this->recalcBukuTabungan($bukuTabunganBaru);
            }
            session()->flash('success', 'Perubahan Berhasil');
        });
    }

    public function getBankUnit()
    {
        return BankSampah::with('gudang');
    }
}
