<?php

use Livewire\Component;
use App\Services\LaporanService;
use App\Services\UserServices;

new class extends Component {
    protected LaporanService $laporanService;
    protected UserServices $userService;

    public function boot(LaporanService $laporanService, UserServices $userService)
    {
        $this->laporanService = $laporanService;
        $this->userService = $userService;
    }

    public function getData()
    {
        $grafik = $this->laporanService->totalSampahPerBulan();
        $ringkasan = $this->laporanService->ringkasanTotalTahunIni();
        $nasabah = $this->userService->totalNasabah();
        $topNasabah = $this->laporanService->topFiveNasabah();
        $komposisi = $this->laporanService->komposisiSampahBulanIni();
        return [
            'grafik' => $grafik,
            'ringkasan' => $ringkasan,
            'nasabah' => $nasabah,
            'topNasabah' => $topNasabah,
            'komposisi' => $komposisi
        ];
    }
};
?>

<div>
    {{-- Simplicity is the consequence of refined emotions. - Jean D'Alembert --}}
    @php
        $data = $this->getData();
    @endphp
    {{-- ======= MOBILE ======= --}}
    @include('panel.grafik.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.grafik.⚡dekstop-mode')
</div>
