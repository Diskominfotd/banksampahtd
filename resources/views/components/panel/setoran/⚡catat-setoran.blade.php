<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Services\SetoranService;
use App\Models\Price;
use App\Models\BankSampah;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    protected UserServices $userService;
    protected SetoranService $setoranService;
    public array $nasabah = [];
    public $selectedNasabah = null;
    public $items = [];
    public array $cart = [];

    public ?int $pageNasabah = 10;
    public ?int $pageSampah = 10;

    public function boot(UserServices $userService, SetoranService $setoranService)
    {
        $this->userService = $userService;
        $this->setoranService = $setoranService;
    }

    public function loadMoreNasabah()
    {
        $this->pageNasabah += 10;
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
        $data = $this->userService->getUserByUnitAndBook()->latest()->paginate(10);
        $this->nasabah = $data->items();
    }
    public function priceAndTrashList()
    {
        $bank = Auth::user()->unit;
        $induk = BankSampah::whereNull('parent_id')->first();

        if ($bank->use_parent_price) {
            return Price::with(['bank', 'trash'])
                ->where('bank_id', $induk->id)
                ->get();
        }

        // Ambil price unit, kalau tidak ada fallback ke harga induk
        return Price::with(['bank', 'trash'])
            ->where('bank_id', $induk->id) // base dari induk
            ->paginate($this->pageSampah)
            ->map(function ($price) use ($bank) {
                // Cek apakah unit punya harga sendiri untuk trash ini
                $unitPrice = Price::where('trash_id', $price->trash_id)->where('bank_id', $bank->id)->first();

                // Kalau ada, pakai harga unit — kalau tidak, pakai harga induk
                if ($unitPrice) {
                    return $unitPrice->load(['bank', 'trash']);
                }

                return $price;
            });
    }
    public function getJenisSampah()
    {
        $data = $this->priceAndTrashList();
        $this->items = $data;
    }
    public function pilihJenisSampah($priceId)
    {
        $item = collect($this->items)->firstWhere('id', $priceId);
        if (!$item) {
            return;
        }
        // Cek apakah sudah ada di cart
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
        $this->setoranService->createSetoran($this->selectedNasabah, $this->cart);

        $this->cart = [];
        $this->selectedNasabah = null;
        $this->resetErrorBag();

        session()->flash('success', 'Setoran berhasil disimpan.');
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
