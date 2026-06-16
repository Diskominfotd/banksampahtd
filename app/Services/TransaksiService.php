<?php
namespace App\Services;

interface TransaksiService
{
    public function createTransaksi(array $data);
    public function reduceSaldo(string $rekening, float $jumlah);
}
