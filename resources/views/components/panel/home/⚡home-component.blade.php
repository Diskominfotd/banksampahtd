<?php

use Livewire\Component;
use App\Services\SetoranService;
use App\Services\UserServices;
use App\Services\TransaksiService;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;
use Illuminate\Support\Facades\Auth;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected SetoranService $setoranService;
    protected UserServices $userService;
    protected TransaksiService $transaksiService;
    public array $bukuTabungan = [];
    public ?int $unitBukuTabungan = null;
    public ?int $nasabahId = null;

    public array $setoranNasabah = [];
    public $pageSetoran = 10;
    public ?string $searchSetoran = '';

    public array $trxNasabah = [];
    public $pageTrx = 10;
    public ?string $searchTrx = '';

    public function mount()
    {
        $this->nasabahId = Auth::id();
    }

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

    public function addBukuTabungan()
    {
        $this->validate([
            'unitBukuTabungan' => 'required|exists:bank_sampahs,id',
        ]);
        $this->userService->createBukuTabungan($this->nasabahId, $this->unitBukuTabungan);
        $data = $this->userService->getBukuTabunganByUserId($this->nasabahId);
        $this->bukuTabungan = $data->toArray();
        $this->alertNotAlowed();
        $this->alert();
    }

    public function getBukuTabungan()
    {
        $data = $this->userService->getBukuTabunganByUserId($this->nasabahId);
        $this->bukuTabungan = $data->toArray();
    }

    public function getSetoranByAuthUser()
    {
        $builder = $this->setoranService->getSetoranByAuthUser();
        $data = $builder->latest()->paginate($this->pageSetoran);
        $this->setoranNasabah = $data->items();
    }
    public function loadMoreSetoran()
    {
        $this->pageSetoran += 10;
        $this->getSetoranByAuthUser();
    }
    public function getTrxByAuthUser()
    {
        $builder = $this->transaksiService->getTrxByAuthUser();
        $data = $builder->latest()->paginate($this->pageTrx);
        $this->trxNasabah = $data->items();
    }
    public function loadMoreTrx()
    {
        $this->pageSetoran += 10;
        $this->getTrxByAuthUser();
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
        $setoranNasabahByLimit = $this->setoranService->getSetoranByUserByLimit();
        $trxNasabahByLimit = $this->transaksiService->getTrxByUserByLimit();
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
            'banksampah' => $this->userService->getBanks()->get(),
            'setoranNasabahByLimit' => $setoranNasabahByLimit,
            'trxNasabahByLimit' => $trxNasabahByLimit
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
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
    @php $data = $this->getData(); @endphp
    @include('panel.home.⚡mobile-mode')
    @include('panel.home.⚡dekstop-mode')
    @script
        <script>
            $wire.on("close-modal", () => {
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
