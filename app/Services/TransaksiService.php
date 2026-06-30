<?php
namespace App\Services;

interface TransaksiService
{
    public function createTransaksi(array $data);
    public function reduceSaldo(string $rekening, float $jumlah);
    public function getTransaksis();
    public function totalPenarikanSaldoNasabah();
    public function penarikanToday();
    public function totalTransaksiNasabah();
    public function getTrxByAuthUser();
    public function getTrxByUserByLimit();
    public function transaksiById(int $id);
    public function getTrxByUniLimit();
    public function bongkarGudang(array $data);
    public function getTrxGudang();
    public function totalPendapatan();
    public function getTrxGudangById(int $id);
}
