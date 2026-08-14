<?php

use Livewire\Component;
use App\Services\SetoranService;
use App\Services\TransaksiService;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Livewire\TraitComponent;
use Livewire\Attributes\On;
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

    public ?int $idTrx = null;
    public ?string $kodeTrx = null;
    public ?float $nilaiTrx = null;
    public ?string $keteranganTrx = null;

    public ?int $idTrxPengeluran = null;
    public ?string $kodeTrxPengeluaran = null;
    public ?float $nilaiTrxPengeluaran = null;
    public ?string $keteranganTrxPengeluaran = null;
    public ?int $bkid = null;

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
        $this->itemTrxPengeluaran = $item;
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
    public function trxDetailEdit($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->getTrxGudangById($id);
        $this->idTrx = $item->id;
        $this->nilaiTrx = $item->total_penarikan;
        $this->keteranganTrx = $item->keterangan;
        $this->kodeTrx = $item->kode;
    }
    public function editTrxGudang()
    {
        $this->validate([
            'nilaiTrx' => ['required', 'numeric', 'min:10000'],
            'keteranganTrx' => ['required', 'string', 'max:255'],
        ]);
        $this->transaksiService->editTrxGudang($this->idTrx, [
            'nilai' => $this->nilaiTrx,
            'keterangan' => $this->keteranganTrx,
        ]);
        $this->reset(['nilaiTrx', 'keteranganTrx']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    #[On('doDeleteTrxGudang')]
    public function doDeleteTrxGudang(string $trxId)
    {
        $trxId = decrypt($trxId);
        $this->transaksiService->deleteTrxGudang($trxId);

        $this->itemTrx = null;
        $this->idTrx = null;
        $this->kodeTrx = null;
        $this->nilaiTrx = null;
        $this->keteranganTrx = null;

        $this->dispatch('close-modal');
        $this->alert();
    }

    public function trxDetailPengeluaran($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->pengeluaranById($id);
        $this->itemTrxPengeluaran = $item;
    }
    public function trxPengeluranDetail($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->pengeluaranById($id);
        $this->idTrxPengeluran = $item->id;
        $this->kodeTrxPengeluaran = $item->kode;
        $this->nilaiTrxPengeluaran = $item->total_penarikan;
        $this->keteranganTrxPengeluaran = $item->keterangan;
        $this->bkid = $item->buku_tabungan_id;
    }

    public function editTrxPengeluaran()
    {
        $this->validate([
            'nilaiTrxPengeluaran' => ['required', 'numeric', 'min:10000'],
            'keteranganTrxPengeluaran' => ['required', 'string', 'max:255'],
            'bkid' => ['nullable', 'numeric', 'min:10000'],
        ]);
        $this->transaksiService->bongkarGudang([
            'total_penarikan' => $this->nilaiTrxPengeluaran,
            'keterangan' => $this->keteranganTrxPengeluaran,
            'buku_tabungan' => $this->bkid,
        ]);
        $this->reset(['totalNilai', 'keterangan', 'bkid']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }
    #[On('doDeleteTrxPengeluaran')]
    public function doDeleteTrxPengeluaran(string $trxId)
    {
        $trxId = decrypt($trxId);
        $this->transaksiService->deleteTrxPengeluaran($trxId);

        $this->itemTrxPengeluaran = null;
        $this->idTrxPengeluran = null;
        $this->kodeTrxPengeluaran = null;
        $this->nilaiTrxPengeluaran = null;
        $this->keteranganTrxPengeluaran = null;
        $this->bkid = null;

        $this->dispatch('close-modal');
        $this->alert();
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
        $totalSetoran = $this->setoranService->totalSaldoTabunganNasbahTersisa();
        $keuntungan = $this->setoranService->saldoBersih();
        $pengeluaran = $this->transaksiService
            ->getPengeluaran()
            ->latest()
            ->paginate($this->pagePgn, ['*'], 'pgnPage');
        $totalStokGudang = $this->setoranService->totalBeratSetoran();
        $totalPenarikanSaldoNasabah = $this->transaksiService->totalPengeluaranByUnit();
        $trx = $this->transaksiService
            ->getTrxGudang()
            ->latest()
            ->paginate($this->pageTrx, ['*'], 'trxPage');
        $totalPendapatan = $this->transaksiService->totalPendapatan();
        $pendapatanbersih = $this->setoranService->pendapatanBersih();
        return [
            'totalSetoran' => $totalSetoran,
            'totalStokGudang' => $totalStokGudang,
            'pengeluaran' => $pengeluaran,
            'keuntungan' => $keuntungan,
            'totalPenarikanSaldoNasabah' => $totalPenarikanSaldoNasabah,
            'totalSetoran' => $totalSetoran,
            'trx' => $trx,
            'totalPendapatan' => $totalPendapatan,
            'pendapatanbersih' => $pendapatanbersih,
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
                $('#wm-edit-trx').modal('hide');
                $('#wm-detail-trx').modal('hide');
                $('#wm-detail-pengeluaran').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
