<?php
namespace App\Services;

interface LaporanService
{
    public function totalSampahPerBulan();
    public function ringkasanTotalTahunIni();
    public function topFiveNasabah();
    public function komposisiSampahBulanIni();
}
