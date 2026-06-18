<?php
namespace App\Services\Impl;

use App\Models\BukuTabungan;
use App\Models\Transaksi;
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
            ]);
        });
    }

    public function getTransaksis()
    {
        $user = $this->checkUser();

        return Transaksi::with([
            'owner.bukutabungans' => function ($q) use ($user) {
                $q->where('bank_id', $user->unit->id)->with('bank');
            },
        ]);
    }

    public function totalPenarikanSaldoNasabah()
    {
        $today = $this->getTransaksis()->whereDate('created_at', today())->sum('total_penarikan');
        $yesterday = $this->getTransaksis()
            ->whereDate('created_at', today()->subDay())
            ->sum('total_penarikan');

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
