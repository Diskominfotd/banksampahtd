<?php

use Livewire\Component;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Livewire\TraitComponent;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected UserServices $userService;
    public int $perPage = 10;
    public ?string $keyword = '';

    //create porperties
    public ?string $nama = '';
    public ?string $kode = '';
    public ?string $alamat = '';
    public ?string $jamBuka = '08:00';
    public ?string $jamTutup = '16:00';
    public ?string $telepon = '';

    //update porperties
    public ?string $namaUnit = '';
    public ?string $kodeUnit = '';
    public ?string $alamatUnit = '';
    public ?string $jamBukaUnit = '';
    public ?string $jamTutupUnit = '';
    public ?string $teleponUnit = '';
    public $unitId;

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
    public function createUnit()
    {
        $this->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['string', 'max:20', Rule::unique('bank_sampahs', 'kode_bank')],
            'alamat' => ['required', 'string', 'max:255'],
            'jamBuka' => ['required'],
            'jamTutup' => ['required'],
            'telepon' => ['required', 'string', 'max:20', 'regex:/^08[0-9]+$/'],
        ]);
        $this->userService->createUnit([
            'nama' => $this->nama,
            'kode_bank' => $this->kode,
            'alamat' => $this->alamat,
            'jam_buka' => $this->jamBuka,
            'jam_tutup' => $this->jamTutup,
            'telepon' => $this->telepon,
        ]);
        $this->reset(['nama', 'kode', 'alamat', 'jamBuka', 'jamTutup', 'telepon']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    public function detail(string $id)
    {
        $id = decrypt($id);
        $item = $this->userService->getUnitById($id);
        $this->unitId = $item->id;
        $this->namaUnit = $item->nama;
        $this->kodeUnit = $item->kode_bank;
        $this->alamatUnit = $item->alamat;
        $this->jamBukaUnit = $item->jam_buka;
        $this->jamTutupUnit = $item->jam_tutup;
        $this->teleponUnit = $item->telepon;
    }

    public function editUnit()
    {
        $this->validate([
            'namaUnit' => ['required', 'string', 'max:255'],
            'kodeUnit' => ['string', 'max:20', Rule::unique('bank_sampahs', 'kode_bank')
            ->ignore($this->unitId)],
            'alamatUnit' => ['required', 'string', 'max:255'],
            'jamBukaUnit' => ['required'],
            'jamTutupUnit' => ['required'],
            'teleponUnit' => ['required', 'string', 'max:20', 'regex:/^08[0-9]+$/'],
        ]);
        $this->userService->updateUnit($this->unitId,[
            'nama' => $this->namaUnit,
            'kode_bank' => $this->kodeUnit,
            'alamat' => $this->alamatUnit,
            'jam_buka' => $this->jamBukaUnit,
            'jam_tutup' => $this->jamTutupUnit,
            'telepon' => $this->teleponUnit,
        ]);
        $this->reset(['namaUnit', 'kodeUnit', 'alamatUnit', 'jamBukaUnit', 'jamTutupUnit', 'teleponUnit']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }
    public function getData()
    {
        $builder = $this->userService->unitBuilder();
        if ($this->keyword) {
            $builder->where('nama', 'LIKE', '%' . $this->keyword . '%');
            $this->resetPage();
        }
        $unit = $builder->latest()->paginate($this->perPage);
        return [
            'unit' => $unit,
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
    @include('panel.unit.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.unit.⚡dekstop-mode')

    @script
        <script>
            $wire.on("close-modal", () => {
                $('#wm-tambah-unit').modal('hide');
                $('#wm-edit-unit').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
