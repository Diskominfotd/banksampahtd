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

    public $pagePgn = 7;
    public $pageTrx = 7;

    public int $totalNilai;
    public ?string $keterangan = null;
    public ?int $stokGudang = 0;

    public int $totalNilaiPengeluaran;
    public ?string $keteranganPengeluaran = null;

    public $itemTrxPengeluaran = null;
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
    public function loadPerpageTrx()
    {
        $this->pageTrx += 7;
    }

    public function loadPerpagePengeluaran()
    {
        $this->pagePgn += 7;
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

    public function addPengeluaran()
    {
        $this->validate([
            'totalNilaiPengeluaran' => ['required', 'numeric', 'min:1'],
            'keteranganPengeluaran' => ['required', 'string', 'max:255'],
        ]);
        $this->transaksiService->buatPengeluaran([
            'total_penarikan' => $this->totalNilaiPengeluaran,
            'keterangan' => $this->keteranganPengeluaran ?? null,
        ]);
        $this->reset(['totalNilaiPengeluaran', 'keteranganPengeluaran']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }
    public function trxDetail($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->getTrxGudangById($id);
        $this->itemTrx = $item;
    }
    public function trxDetailPengeluaran($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->pengeluaranById($id);
        $this->itemTrxPengeluaran = $item;
    }

    public function doTrxGudang()
    {
        $this->validate([
            'totalNilai' => ['required', 'numeric', 'min:10000'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);
        $this->transaksiService->bongkarGudang([
            'total_penarikan' => $this->totalNilai,
            'keterangan' => $this->keterangan ?? null,
        ]);
        $this->reset(['totalNilai', 'keterangan']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    public function getData()
    {
        $totalBeratSetoran = $this->setoranService->totalBeratSetoran();
        $pengeluaran = $this->transaksiService
            ->getPengeluaran()
            ->latest()
            ->paginate($this->pagePgn, ['*'], 'pgnPage'); // <- pageName unik
        $totalStokGudang = $this->setoranService->totalBeratSetoran();
        $totalPenarikanSaldoNasabah = $this->transaksiService->totalPengeluaranByUnit();
        $trx = $this->transaksiService
            ->getTrxGudang()
            ->latest()
            ->paginate($this->pageTrx, ['*'], 'trxPage'); // <- pageName unik
        $totalPendapatan = $this->transaksiService->totalPendapatan();
        $pendapatanbersih = $this->setoranService->pendapatanBersih();

        
        return [
            'totalStokGudang' => $totalStokGudang,
            'pengeluaran' => $pengeluaran,
            'totalPenarikanSaldoNasabah' => $totalPenarikanSaldoNasabah,
            'totalBeratSetoran' => $totalBeratSetoran,
            'trx' => $trx,
            'totalPendapatan' => $totalPendapatan,
            'pendapatanbersih' => $pendapatanbersih
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
                $('#wm-trx-pengeluaran').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
