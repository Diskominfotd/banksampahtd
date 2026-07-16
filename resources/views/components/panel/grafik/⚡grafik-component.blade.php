<?php

use Livewire\Component;
use App\Services\LaporanService;
use App\Services\UserServices;

new class extends Component {
    protected LaporanService $laporanService;
    protected UserServices $userService;

    public string $bulan = '';
    public string $tahun = '';
    public string $tahunTotalSampah = '';
    public string $tahunTotalRingkasan = '';

    public function mount()
    {
        $this->tahun = now()->year;
        $this->tahunTotalSampah = now()->year;
        $this->tahunTotalRingkasan = now()->year;
    }
    public function boot(LaporanService $laporanService, UserServices $userService)
    {
        $this->laporanService = $laporanService;
        $this->userService = $userService;
    }

    public function getData()
    {
        $grafik = $this->laporanService->totalSampahPerBulan($this->tahunTotalSampah);
        $ringkasan = $this->laporanService->ringkasanTotalTahunIni($this->tahunTotalRingkasan);
        $nasabah = $this->userService->totalNasabah();
        $topNasabah = $this->laporanService->topFiveNasabah();
        $komposisi = $this->laporanService->komposisiSampahBulanIni($this->tahun, $this->bulan);
        //    return dd(json_encode($ringkasan, JSON_PRETTY_PRINT));
        return [
            'grafik' => $grafik,
            'ringkasan' => $ringkasan,
            'nasabah' => $nasabah,
            'topNasabah' => $topNasabah,
            'komposisi' => $komposisi,
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
