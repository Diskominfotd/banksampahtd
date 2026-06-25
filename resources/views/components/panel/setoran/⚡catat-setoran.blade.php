<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Services\SetoranService;
use App\Models\Price;
use App\Models\BankSampah;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;

new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected UserServices $userService;
    protected SetoranService $setoranService;
    public array $nasabah = [];
    public $selectedNasabah = null;
    public $items = [];
    public array $cart = [];

    public ?int $pageNasabah = 10;
    public ?string $searchNasabah = '';
    public ?int $pageSampah = 10;

    public ?string $searchJenis = '';

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function boot(UserServices $userService, SetoranService $setoranService)
    {
        $this->userService = $userService;
        $this->setoranService = $setoranService;
    }

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }

    public function updatedSearchJenis()
    {
        $this->pageSampah = 10;
        $this->getJenisSampah();
    }

    public function loadMoreNasabah()
    {
        $this->pageNasabah += 10;
        $this->getNasabah();
    }
    public function loadMoreItemSampah()
    {
        $this->pageSampah += 10;
        $this->getJenisSampah();
    }
    public function pilihNasabah($id)
    {
        $this->selectedNasabah = collect($this->nasabah)->firstWhere('id', $id);
        $this->dispatch('close-modal');
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
    public function priceAndTrashList()
    {
        $bank = Auth::user()->unit;
        $induk = BankSampah::whereNull('parent_id')->first();
        $query = Price::with(['bank', 'trash'])->where('bank_id', $induk->id);
        if ($this->searchJenis) {
            $query->whereHas('trash', function ($q) {
                $q->where('nama', 'like', "%{$this->searchJenis}%");
            });
        }
        $prices = $query->paginate($this->pageSampah)->getCollection();

        if ($bank->use_parent_price) {
            return $prices;
        }

        return $prices->map(function ($price) use ($bank) {
            $unitPrice = Price::where('trash_id', $price->trash_id)->where('bank_id', $bank->id)->first();

            return $unitPrice ? $unitPrice->load(['bank', 'trash']) : $price;
        });
    }

    public function getJenisSampah()
    {
        $this->items = $this->priceAndTrashList();
    }
    public function pilihJenisSampah($priceId)
    {
        $item = collect($this->items)->firstWhere('id', $priceId);
        if (!$item) {
            return;
        }
        $existing = collect($this->cart)->search(fn($c) => $c['price_id'] == $priceId);
        if ($existing !== false) {
            return;
        }
        $this->cart[] = [
            'trash_id' => $item->trash->id,
            'price_id' => $item->id,
            'nama' => $item->trash->nama,
            'harga' => $item->harga ?? 0,
            'type' => $item->type,
            'berat' => 0,
        ];
        $this->dispatch('close-modal');
    }

    public function updateBerat($index, $berat)
    {
        $value = (float) $berat;
        $this->cart[$index]['berat'] = $value;
    }
    public function removeCart($index)
    {
        array_splice($this->cart, $index, 1);
    }

    public function getCartTotalProperty()
    {
        return collect($this->cart)->sum(fn($c) => $c['harga'] * $c['berat']);
    }

    public function getItemsFilteredProperty()
    {
        return collect($this->items)->filter(function ($item) {
            if (!$this->searchJenis) {
                return true;
            }
            return str_contains(strtolower($item->trash->nama), strtolower($this->searchJenis));
        });
    }

    public function simpanSetoran()
    {
        if (!$this->selectedNasabah) {
            $this->addError('nasabah', 'Nasabah belum dipilih.');
            return;
        }
        if (empty($this->cart)) {
            $this->addError('cart', 'Tambahkan minimal satu item setoran.');
            return;
        }
        foreach ($this->cart as $i => $c) {
            if ($c['berat'] <= 0) {
                $this->addError("cart_{$i}", "Berat item \"{$c['nama']}\" harus diisi dan lebih dari 0.");
                return;
            }
        }
        $bankId = Auth::user()->unit->id;
        $this->setoranService->createSetoran($this->selectedNasabah, $this->cart, $bankId);
        $this->cart = [];
        $this->selectedNasabah = null;
        $this->resetErrorBag();
        session()->flash('success', 'Setoran berhasil disimpan.');
        $this->alertPopUp();
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

    {{-- ======= MOBILE ======= --}}
    @include('panel.setoran.⚡mobile-mode')
    {{-- ======= DESKTOP ======= --}}
    @include('panel.setoran.⚡dekstop-mode')
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-pilih-nasabah').modal('hide');
                $('#wm-pilih-jenis').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
