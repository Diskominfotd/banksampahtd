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
    public ?string $orgId = '';
    public ?string $namaOrganisasi = '';
    public ?string $keyword = '';
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
    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function createOrganisasi()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:organisasis,nama',
        ]);
        $this->userService->createOrganisasi([
            'nama' => $this->nama,
        ]);
        $this->reset('nama');
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }
    public function detail(string $id)
    {
        $id = decrypt($id);
        $item = $this->userService->getOrganisasiById($id);
        $this->namaOrganisasi = $item->nama;
        $this->orgId = $item->id;
    }
    public function editOrganisasi()
    {
        $this->validate([
            'namaOrganisasi' => 'required|string|max:50|unique:organisasis,nama,' . $this->orgId,
        ]);
        $this->userService->updateOrganisasi($this->orgId, [
            'nama' => $this->namaOrganisasi,
        ]);
        $this->reset('namaOrganisasi');
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }
    public function getData()
    {
        $builder = $this->userService->organisasiBuilder();
        if ($this->keyword) {
            $builder->where('nama', 'LIKE', '%' . $this->keyword . '%');
            $this->resetPage();
        }
        $org = $builder->latest()->paginate($this->perPage);
        return [
            'org' => $org,
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
    {{-- He who is contented is rich. - Laozi --}}

    @php
        $data = $this->getData();
    @endphp

    {{-- ======= MOBILE ======= --}}
    @include('panel.organisasi.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.organisasi.⚡dekstop-mode')

    @script
        <script>
            $wire.on("close-modal", () => {
                $('#wm-tambah-org').modal('hide');
                $('#wm-edit-org').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
