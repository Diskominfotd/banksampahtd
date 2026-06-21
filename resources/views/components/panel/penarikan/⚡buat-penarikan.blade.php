<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Services\TransaksiService;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
new class extends Component {
    use WithPagination;

    protected UserServices $userService;
    protected TransaksiService $transaksiService;

    public array $nasabah = [];
    public array $selectedNasabah = [];

    public ?int $pageNasabah = 10;
    public ?string $searchNasabah = '';

    public function boot(UserServices $userService, TransaksiService $transaksiService)
    {
        $this->userService = $userService;
        $this->transaksiService = $transaksiService;
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function loadMoreNasabah()
    {
        $this->pageNasabah += 10;
        $this->getNasabah();
    }
    public function getNasabah()
    {
        $builder = $this->userService->getUserByUnitAndBook();
        if ($this->searchNasabah) {
            $builder->where(function ($q) {
                $q->where('name', 'like', "%{$this->searchNasabah}%")->orWhereHas('bukutabungans', function ($q) {
                    $q->where('nomor_rekening', 'like', "%{$this->searchNasabah}%");
                });
            });
        }
        $data = $builder->latest()->paginate($this->pageNasabah);
        $this->nasabah = $data->items();
    }
    public function updatedSearchNasabah()
    {
        $this->resetPage('pageNasabah');
        $this->getNasabah();
    }
    public function pilihNasabah($id)
    {
        if (collect($this->selectedNasabah)->contains('user_id', $id)) {
            $this->dispatch('close-modal');
            return;
        }
        $item = $this->userService->getUserByUnitAndBook()->where('users.id', $id)->first();
        $unitId = Auth::user()->unit->id;
        $buku = $item->bukutabungans->firstWhere('bank_id', $unitId);

        $this->selectedNasabah[] = [
            'user_id' => $id,
            'name' => $item->name,
            'buku_tabungan_id' => $buku->id,
            'rekening' => $buku->nomor_rekening,
            'saldo' => $buku->saldo,
            'jumlah' => 0,
        ];

        $this->dispatch('close-modal');
    }

    public function simpanPenarikan()
    {
        $this->validate(
            [
                'selectedNasabah.*.jumlah' => 'required|numeric|min:10000',
            ],
            [
                'selectedNasabah.*.jumlah.min' => 'minimal Rp 10.000.',
            ],
        );

        foreach ($this->selectedNasabah as $i => $item) {
            if ($item['jumlah'] > $item['saldo']) {
                $this->addError("selectedNasabah.{$i}.jumlah", 'Jumlah melebihi saldo.');
                return;
            }
        }
        foreach ($this->selectedNasabah as $item) {
            $this->transaksiService->createTransaksi($item);
            $this->transaksiService->reduceSaldo($item['rekening'], $item['jumlah']);
        }

        $this->selectedNasabah = [];
        $this->dispatch('notify', message: 'Penarikan berhasil disimpan.');
    }
    public function removeCart($i)
    {
        array_splice($this->selectedNasabah, $i, 1);
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
    {{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
    @include('components.panel.penarikan.⚡mobile-mode')
    @include('components.panel.penarikan.⚡dekstop-mode')
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-pilih-nasabah').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
