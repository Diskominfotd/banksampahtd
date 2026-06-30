<?php

use Livewire\Component;
use App\Services\SetoranService;
use App\Services\TransaksiService;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Livewire\TraitComponent;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected TransaksiService $transaksiService;
    protected SetoranService $setoranService;

    public $pageSetoran = 7;
    public $pageTrx = 7;

    public int $totalBerat;
    public int $totalNilai;

    public ?int $stokGudang = 0;

    public $itemSetoranDetail = null;
    public $itemTrx = null;

    public function boot(SetoranService $setoranService, TransaksiService $transaksiService)
    {
        $this->setoranService = $setoranService;
        $this->transaksiService = $transaksiService;
    }
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function loadPerpage()
    {
        $this->pageTrx += 7;
    }
    public function setoranDetail($id)
    {
        $id = decrypt($id);
        $item = $this->setoranService->getSetoranByIdNasabah($id);
        $this->itemSetoranDetail = $item;
    }

    public function isiGudang()
    {
        $item = $this->setoranService->getGudangByUnit()->first();
        $this->stokGudang = $item->berat;
    }

    public function trxDetail($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->getTrxGudangById($id);
        $this->itemTrx = $item;
    }

    public function doTrxGudang()
    {
        $this->validate([
            'totalBerat' => ['required', 'numeric', 'min:1'],
            'totalNilai' => ['required', 'numeric', 'min:10000'],
        ]);
        if ($this->stokGudang < $this->totalBerat) {
            $this->addError('totalBerat', 'Jumlah melebihi stok.');
            return;
        }
        $this->transaksiService->bongkarGudang([
            'total_penarikan' => $this->totalNilai,
            'total_berat' => $this->totalBerat,
        ]);
        $this->reset(['totalBerat', 'totalNilai']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    public function getData()
    {
        $totalBeratSetoran = $this->setoranService->totalBeratSetoran();
        $setoran = $this->setoranService->getSetoranByUnit()->latest()->paginate($this->pageSetoran);
        $totalStokGudang = $this->setoranService->totalStokGudang();
        $totalPenarikanSaldoNasabah = $this->transaksiService->totalPenarikanSaldoNasabah();
        $trx = $this->transaksiService->getTrxGudang()->latest()->paginate($this->pageTrx);
        $totalPendapatan = $this->transaksiService->totalPendapatan();
        return [
            'totalStokGudang' => $totalStokGudang,
            'setoran' => $setoran,
            'totalPenarikanSaldoNasabah' => $totalPenarikanSaldoNasabah,
            'totalBeratSetoran' => $totalBeratSetoran,
            'trx' => $trx,
            'totalPendapatan' => $totalPendapatan,
        ];
    }
};
?>

<div x-data x-init="if (!Alpine.store('sheet')) {
    Alpine.store('sheet', {
        active: null,
        show(name) { this.active = name },
        hide() { this.active = null },
        is(name) { return this.active === name },
    })
}">
    {{-- An unexamined life is not worth living. - Socrates --}}
    @php
        $data = $this->getData();
    @endphp


    {{-- ======= MOBILE ======= --}}
    @include('panel.gudang.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.gudang.⚡dekstop-mode')
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-bongkar-gudang').modal('hide');
                $('#wm-detail-setoran-id').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
