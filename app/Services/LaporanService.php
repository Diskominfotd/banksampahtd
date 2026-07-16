<?php
namespace App\Services;

interface LaporanService
{
    public function totalSampahPerBulan(string $tahun);
    public function ringkasanTotalTahunIni(string $tahun);
    public function topFiveNasabah();
    public function komposisiSampahBulanIni(string $tahun, string $bulan);
}
