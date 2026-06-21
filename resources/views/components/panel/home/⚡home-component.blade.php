<?php

use Livewire\Component;
use App\Services\SetoranService;
use App\Services\UserServices;
use App\Services\TransaksiService;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;
new class extends Component {
    use WithPagination;
    protected SetoranService $setoranService;
    protected UserServices $userService;
    protected TransaksiService $transaksiService;

    public function boot(SetoranService $setoranService, UserServices $userService, TransaksiService $transaksiService)
    {
        $this->setoranService = $setoranService;
        $this->userService = $userService;
        $this->transaksiService = $transaksiService;
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function getData()
    {
        $totalBeratSetoran = $this->setoranService->totalBeratSetoran();
        $totalSaldoSetoran = $this->setoranService->totalSaldoSetoran();
        $totalNasabah = $this->userService->totalNasabah();
        $totalPenarikanSaldoNasabah = $this->transaksiService->totalPenarikanSaldoNasabah();
        $setoranTerbaru = $this->setoranService->setoranToday();
        $transaksiTerbaru = $this->transaksiService->penarikanTerbaru();
        $totalSaldoNasabah = $this->userService->totalSaldoNasbah();
        $totalRekeningNasabah = $this->userService->totalBukuTabunganNasabah();
        $totalSetoranNasabah = $this->setoranService->totalSaldoSetoranNasbah();
        $totalPenarikanNasabah = $this->transaksiService->totalTransaksiNasabah();

        return [
            'totalBeratSetoran' => $totalBeratSetoran,
            'totalSaldoSetoran' => $totalSaldoSetoran,
            'totalNasabah' => $totalNasabah,
            'totalPenarikanSaldoNasabah' => $totalPenarikanSaldoNasabah,
            'setoranTerbaru' => $setoranTerbaru,
            'transaksiTerbaru' => $transaksiTerbaru,
            'totalSaldoNasabah' => $totalSaldoNasabah,
            'totalRekeningNasabah' => $totalRekeningNasabah,
            'totalSetoranNasabah' => $totalSetoranNasabah,
            'totalPenarikanNasabah' => $totalPenarikanNasabah,
        ];
    }
};
?>

<div>
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
    @php
        $data = $this->getData();
    @endphp
    @include('panel.home.⚡dekstop-mode')
    @include('panel.home.⚡mobile-mode')
</div>
