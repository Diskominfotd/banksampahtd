<?php

use Livewire\Component;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected UserServices $userService;

    public int $perPage = 10;
    public ?string $nama = '';
    public ?string $namaKategori = '';
    public ?string $categoryId = '';

    public ?string $keyword = '';

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function createCategory()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:categories,name',
        ]);
        $this->userService->createCategory([
            'name' => $this->nama,
        ]);
        $this->reset('nama');
        $this->dispatch('close-modal');
        $this->alert();
    }
    public function detail(string $id)
    {
        $id = decrypt($id);
        $item = $this->userService->categoryById($id);
        $this->namaKategori = $item->name;
        $this->categoryId = $item->id;
    }
    public function editCategory()
    {
        $this->validate([
            'namaKategori' => 'required|string|max:50|unique:categories,name,' . $this->categoryId,
        ]);
        $this->userService->updateCategory(
            [
                'name' => $this->namaKategori,
            ],
            $this->categoryId,
        );
        $this->reset('namaKategori');
        $this->dispatch('close-modal');
        $this->alert();
    }

    #[On('doDelete')]
    public function delete()
    {
        $this->userService->delete($this->categoryId);
    }

    public function alertDelete(string $categoryId)
    {
        $this->categoryId = decrypt($categoryId);

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
        $categoriyBuilder = $this->userService->categoriesBuilder();
        if ($this->keyword) {
            $categoriyBuilder->where('name', 'LIKE', '%' . $this->keyword . '%');
            $this->resetPage();
        }
        $categories = $categoriyBuilder->latest()->paginate($this->perPage);
        return [
            'categories' => $categories,
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
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    @php
        $data = $this->getData();
    @endphp
    {{-- ======= MOBILE ======= --}}
    @include('panel.category.⚡mobile-mode')

    {{-- ======= DEKSTOP ======= --}}
    @include('panel.category.⚡dekstop-mode')

    @script
        <script>
            $wire.on("close-modal", () => {
                $('#wm-tambah-kategori').modal('hide');
                $('#wm-edit-kategori').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
