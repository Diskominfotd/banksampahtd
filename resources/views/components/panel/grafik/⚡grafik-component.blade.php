<?php

use Livewire\Component;
use App\Services\LaporanService;
use App\Services\UserServices;
use App\Services\SetoranService;

new class extends Component {
    protected LaporanService $laporanService;
    protected UserServices $userService;
    protected SetoranService $setoranService;

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
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function boot(LaporanService $laporanService, UserServices $userService, SetoranService $setoranService)
    {
        $this->laporanService = $laporanService;
        $this->userService = $userService;
        $this->setoranService = $setoranService;
    }

    public function getData()
    {
        $grafik = $this->laporanService->totalSampahPerBulan($this->tahunTotalSampah);
        $ringkasan = $this->laporanService->ringkasanTotalTahunIni($this->tahunTotalRingkasan);
        $nasabah = $this->userService->totalNasabah();
        $topNasabah = $this->laporanService->topFiveNasabah();
        $komposisi = $this->laporanService->komposisiSampahBulanIni($this->tahun, $this->bulan);
        $keuntungan = $this->setoranService->saldoBersih();
        $estimasiStok = $this->setoranService->estimasiSisaStokSampah();
        $estimasiLaba = $this->setoranService->estimasiKuntungan();
        return [
            'grafik' => $grafik,
            'ringkasan' => $ringkasan,
            'nasabah' => $nasabah,
            'topNasabah' => $topNasabah,
            'komposisi' => $komposisi,
            'keuntungan' => $keuntungan,
            'estimasiStok' => $estimasiStok,
            'estimasiLaba' => $estimasiLaba,
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
