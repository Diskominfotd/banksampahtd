<?php
namespace App\Services\Impl;

use App\Models\BukuTabungan;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\TransaksiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiServiceImpl implements TransaksiService
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

    public function reduceSaldo(string $rekening, float $jumlah)
    {
        return DB::transaction(function () use ($rekening, $jumlah) {
            BukuTabungan::where('nomor_rekening', $rekening)->decrement('saldo', $jumlah);
        });
    }

    public function createTransaksi(array $data)
    {
        return DB::transaction(function () use ($data) {
            Transaksi::create([
                'total_penarikan' => $data['jumlah'],
                'sisa_saldo' => $data['saldo'] - $data['jumlah'],
                'tanggal_transaksi' => Carbon::now(),
                'owner_id' => $data['user_id'],
                'admin_id' => $this->checkUser()->id,
                'buku_tabungan_id' => $data['buku_tabungan_id'],
            ]);
            session()->flash('success', 'Berhasil');
        });
    }

    public function getTransaksis()
    {
        $user = $this->checkUser();
        $unitId = $user->unit->id;

        return Transaksi::with(['owner', 'bukutabungan.bank'])
        ->whereHas('bukutabungan', function ($q) use ($unitId) {
            $q->where('bank_id', $unitId);
        });
    }

    public function penarikanToday()
    {
        $todayDate = now()->startOfDay();
        return $this->getTransaksis()->whereDate('created_at', $todayDate)
        ->latest()->limit(5)->get();
    }

    public function totalPenarikanSaldoNasabah()
    {
        $total = $this->getTransaksis()->sum('total_penarikan');
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->getTransaksis()->whereDate('created_at', $todayDate)
        ->sum('total_penarikan');
        $yesterday = $this->getTransaksis()->whereDate('created_at', $yesterdayDate)
        ->sum('total_penarikan');

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
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

    public function totalTransaksiNasabah()
    {
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->transaksiByAuthUser()->whereDate('created_at', $todayDate)->sum('total_penarikan');
        $yesterday = $this->transaksiByAuthUser()->whereDate('created_at', $yesterdayDate)->sum('total_penarikan');

        return [
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
            ->latest()
            ->limit(5)
            ->get();
    }
}
