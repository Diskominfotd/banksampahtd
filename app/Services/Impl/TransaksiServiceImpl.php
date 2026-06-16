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

    public function getTransaksis() {
        $user = $this->checkUser();

        return Transaksi::with('owner.bukutabungans');
    }
}
