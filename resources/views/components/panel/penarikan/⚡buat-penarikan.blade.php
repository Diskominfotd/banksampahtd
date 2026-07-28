<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Services\TransaksiService;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Livewire\TraitComponent;
use Illuminate\Support\Facades\Auth;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected UserServices $userService;
    protected TransaksiService $transaksiService;

    public array $nasabah = [];
    public array $selectedNasabah = [];

    public ?int $pageNasabah = 10;
    public ?string $searchNasabah = '';

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
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
        $unit = Auth::user();
        $builder = $this->userService->getUserByUnitAndBook();
        if ($this->searchNasabah) {
            $builder->where(function ($q) use ($unit) {
                $q->whereHas('bukutabungans', function ($q) use ($unit) {
                    $q->where('nomor_rekening', 'like', "%{$this->searchNasabah}%");
                    if ($unit->unit->parent_id) {
                        $q->where('bank_id', $unit->unit->id);
                    }
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
        if (!$this->selectedNasabah) {
            session()->flash('error', 'Pilih minimal 1 nasabah');
            $this->alertPopUp();
            return;
        }
        $this->validate(
            [
                'selectedNasabah.*.jumlah' => 'required|numeric|min:5000',
            ],
            [
                'selectedNasabah.*.jumlah.min' => 'minimal Rp 50.000.',
            ],
        );
        foreach ($this->selectedNasabah as $i => $item) {
            if ($item['jumlah'] > $item['saldo']) {
                $this->addError("selectedNasabah.{$i}.jumlah", 'Jumlah melebihi saldo.');
                return;
            }

            if ($item['jumlah'] > $item['saldo'] - 5000) {
                $this->addError("selectedNasabah.{$i}.jumlah", 'Saldo minimal yang harus tersisa adalah Rp 5.000.');
                return;
            }
        }
        foreach ($this->selectedNasabah as $item) {
            $this->transaksiService->createTransaksi($item);
            $this->transaksiService->reduceSaldo($item['rekening'], $item['jumlah']);
        }

        $this->selectedNasabah = [];
        $this->dispatch('notify', message: 'Penarikan berhasil disimpan.');
        $this->alertPopUp();
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
