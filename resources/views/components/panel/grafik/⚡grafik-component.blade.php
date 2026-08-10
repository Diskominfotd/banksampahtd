<?php

use Livewire\Component;
use App\Services\LaporanService;
use App\Services\UserServices;
use App\Services\SetoranService;
use App\Services\TransaksiService;
use App\Models\EstimasiPersediaan;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    protected LaporanService $laporanService;
    protected UserServices $userService;
    protected SetoranService $setoranService;
    protected TransaksiService $transaksiService;

    public string $bulan = '';
    public string $tahun = '';
    public string $tahunTotalSampah = '';
    public string $tahunTotalRingkasan = '';

    // TAMBAHAN: Laporan Keuangan
    public $estimasiPersediaan = 0;
    public ?string $keteranganEstimasi = null;

    public function mount()
    {
        $this->tahun = now()->year;
        $this->tahunTotalSampah = now()->year;
        $this->tahunTotalRingkasan = now()->year;

        // TAMBAHAN: ambil nilai estimasi persediaan gudang yang tersimpan untuk unit ini
        $unitId = Auth::user()->unit->id;
        $estimasi = EstimasiPersediaan::where('bank_id', $unitId)->first();
        $this->estimasiPersediaan = $estimasi->nilai ?? 0;
        $this->keteranganEstimasi = $estimasi->keterangan ?? null;
    }

    public function boot(
        LaporanService $laporanService,
        UserServices $userService,
        SetoranService $setoranService,
        TransaksiService $transaksiService,
    ) {
        $this->laporanService = $laporanService;
        $this->userService = $userService;
        $this->setoranService = $setoranService;
        $this->transaksiService = $transaksiService;
    }

    // TAMBAHAN: logout (dipanggil dari navbar, wajib ada di setiap component yang include navbar)
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

    // TAMBAHAN: simpan input manual Estimasi Nilai Persediaan Gudang
    public function simpanEstimasiPersediaan()
    {
        $this->validate([
            'estimasiPersediaan' => ['required', 'numeric', 'min:0'],
            'keteranganEstimasi' => ['nullable', 'string', 'max:255'],
        ]);

        $unitId = Auth::user()->unit->id;

        EstimasiPersediaan::updateOrCreate(
            ['bank_id' => $unitId],
            [
                'nilai' => $this->estimasiPersediaan,
                'keterangan' => $this->keteranganEstimasi,
            ],
        );

        $this->dispatch('close-modal');
    }

    public function getData()
    {
        $grafik = $this->laporanService->totalSampahPerBulan($this->tahunTotalSampah);
        $ringkasan = $this->laporanService->ringkasanTotalTahunIni($this->tahunTotalRingkasan);
        $nasabah = $this->userService->totalNasabah();
        $topNasabah = $this->laporanService->topFiveNasabah();
        $komposisi = $this->laporanService->komposisiSampahBulanIni($this->tahun, $this->bulan);

        // TAMBAHAN: data Laporan Keuangan
        // Keuntungan Kas = Total Kas Masuk - Total Kas Keluar (sama seperti "Sisa Kas" di halaman Gudang)
        $keuntunganKas = $this->setoranService->pendapatanBersih();

        // Keuntungan Bank = Total Nilai Setoran Nasabah - Total Nilai Penarikan Nasabah
        $totalSaldoSetoran = $this->setoranService->totalSaldoSetoran();
        $totalPenarikanSaldoNasabah = $this->transaksiService->totalPengeluaranByUnit();
        $keuntunganBank = ($totalSaldoSetoran['total'] ?? 0) - ($totalPenarikanSaldoNasabah['total'] ?? 0);

        return [
            'grafik' => $grafik,
            'ringkasan' => $ringkasan,
            'nasabah' => $nasabah,
            'topNasabah' => $topNasabah,
            'komposisi' => $komposisi,
            'keuntunganKas' => $keuntunganKas,
            'keuntunganBank' => $keuntunganBank,
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

    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-estimasi-persediaan').modal('hide');
            });
        </script>
    @endscript
</div>