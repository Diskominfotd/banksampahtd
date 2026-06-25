<?php

use Livewire\Component;
use App\Services\TrashServices;
use App\Models\Price;
use App\Models\BankSampah;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\On;
new class extends Component {
    use WithPagination;
    protected TrashServices $trashService;

    // new jenis attribut
    public ?int $kategori;
    public ?int $priceLimit = 10;
    public ?int $perPage = 10;
    public string $nama = '';
    public string $syarat = '';
    public ?int $harga = null;

    // update jenis attribut'
    public ?int $kategoriJenis;
    public ?string $namaJenis;
    public ?string $syaratJenis;
    public ?string $jenisId;

    //search
    public ?string $keyword = '';
    public ?string $searchPrice = '';

    //Price
    public array $prices = [];

    public ?string $trashId;

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
    public function boot(TrashServices $trashService)
    {
        $this->trashService = $trashService;
    }

    public function loadPerpage()
    {
        $this->perPage += 10;
    }

    public function newJenis()
    {
        $rules = [
            'kategori' => 'required|exists:categories,id',
            'nama' => 'required|string|max:100',
            'syarat' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
        ];
        $this->validate($rules);
        $this->trashService->createJenis([
            'category_id' => $this->kategori,
            'nama' => $this->nama,
            'syarat' => $this->syarat,
            'harga' => $this->harga,
        ]);
        $this->reset(['kategori', 'nama', 'syarat', 'harga']);
        $this->dispatch('close-modal');
    }

    public function detailJenis(string $id)
    {
        $id = decrypt($id);
        $item = $this->trashService->getTrashById($id);
        $this->kategoriJenis = $item->category->id;
        $this->namaJenis = $item->nama;
        $this->syaratJenis = $item->syarat;
        $this->jenisId = $item->id;
    }

    public function editJenis()
    {
        $rules = [
            'kategoriJenis' => 'required|exists:categories,id',
            'namaJenis' => 'required|string|max:100',
            'syaratJenis' => 'required|string|max:100',
        ];
        $this->validate($rules);
        $this->trashService->updateJenis(
            [
                'category_id' => $this->kategoriJenis,
                'nama' => $this->namaJenis,
                'syarat' => $this->syaratJenis,
            ],
            $this->jenisId,
        );
        $this->reset(['kategoriJenis', 'namaJenis', 'syaratJenis']);
        $this->dispatch('close-modal');
    }

    public function priceDetail()
    {
        $bank = Auth::user()->unit;

        $this->prices = $this->trashService
            ->priceList()
            ->filter(fn($price) => str_contains(strtolower($price->trash->nama ?? ''), strtolower($this->searchPrice)))
            ->values()
            ->take($this->priceLimit) // ← ambil sesuai limit
            ->map(
                fn($price) => [
                    'id' => $price->id,
                    'label' => $price->trash->nama,
                    'value' => $price->harga,
                    'is_induk' => !Price::where('trash_id', $price->trash_id)->where('bank_id', $bank->id)->exists(),
                ],
            )
            ->toArray();
    }

    public function loadMorePrices()
    {
        $this->priceLimit += 10;
        $this->priceDetail();
    }

    public function updatedSearchPrice()
    {
        $this->priceLimit = 10; // reset limit saat search berubah
        $this->priceDetail();
    }
    public function updatePrice($index)
    {
        $price = $this->prices[$index];
        $this->trashService->updatePrice($price['id'], [
            'value' => $price['value'],
            'is_induk' => $price['is_induk'],
        ]);
        $this->priceDetail();
    }
    #[On('doDelete')]
    public function delete()
    {
        $this->trashService->deleteTrash($this->trashId);
    }

    public function alertDelete(string $trashId)
    {
        $this->trashId = decrypt($trashId);
        $this->js(
            <<<JS
                Swal.fire({
                title: "Hapus",
                text: "Apakah Anda Yakin ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Batal"
                }).then((result) => {
                   if (result.isConfirmed) {
                Livewire.dispatch('doDelete');
                }
                });
            JS
            ,
        );
    }
    public function getData()
    {
        $categories = $this->trashService->categoryBuilder()->get();
        $trashsBuilder = $this->trashService->priceAndTrashList();
        if ($this->keyword) {
            $trashsBuilder->whereHas('trash', function ($query) {
                $query->where('nama', 'like', '%' . $this->keyword . '%');
            });
            $this->resetPage();
        }
        $trashs = $trashsBuilder->latest()->paginate($this->perPage);
        return [
            'categories' => $categories,
            'trashs' => $trashs,
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
    {{-- The only way to do great work is to love what you do. - Steve Jobs --}}
    @php
        $data = $this->getData();
    @endphp

    {{-- ======= Mobile ======= --}}
    @include('panel.price.⚡mobile-mode')
    {{-- ======= Dekstop ======= --}}
    @include('panel.price.⚡dekstop-mode')
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-tambah-jenis').modal('hide');
                $('#wm-edit-jenis').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
