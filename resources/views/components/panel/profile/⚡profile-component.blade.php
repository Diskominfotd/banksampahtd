<?php

use Livewire\Component;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Livewire\TraitComponent;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;
    protected UserServices $userService;
    use TraitComponent;
    public $fotoProfile;
    public $fotoProfileLama;

    //unit properties
    public ?string $namaBank = '';
    public ?string $hariBuka = '';
    public ?string $kodeBank = '';
    public ?string $alamatBank = '';
    public ?string $jamBuka = '';
    public ?string $jamTutup = '';
    public ?string $nomorTelepon = '';

    //unit properties
    public ?string $namaLengkap = '';
    public ?string $email = '';
    public ?string $nomorTeleponNasabah = '';
    public ?int $nasabah = 0;

    //password
    public ?string $password = '';

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        $unit = $this->userService->getBankByUserId(Auth::user()->bank_sampah_id);
        $this->namaBank = $unit->nama;
        $this->kodeBank = $unit->kode_bank;
        $this->alamatBank = $unit->alamat;
        $this->jamBuka = $unit->jam_buka;
        $this->jamTutup = $unit->jam_tutup;
        $this->nomorTelepon = $unit->telepon;
        $this->nasabah = $this->userService->nasabahAktifByBook(Auth::user()->bank_sampah_id);
        $this->hariBuka = $unit->hari_buka;
    }

    public function profileDetail()
    {
        $item = $this->userService->getUserById(Auth::user()->id);
        $this->namaLengkap = $item->name;
        $this->email = $item->email;
        $this->nomorTeleponNasabah = $item->nomor_hp;
        $this->fotoProfileLama = $item->avatar;
    }

    public function editBankSampah()
    {
        $this->validate([
            'namaBank' => ['required', 'string', 'max:255'],
            'alamatBank' => ['required', 'string', 'max:255'],
            'nomorTelepon' => ['required', 'string', 'max:20', 'regex:/^08[0-9]+$/'],
            'hariBuka' => ['required', 'string'],
            'jamBuka' => ['required', 'date_format:H:i,H:i:s'],
            'jamTutup' => ['required', 'date_format:H:i,H:i:s', 'after:jamBuka'],
        ]);

        $this->userService->updateBankSampah(Auth::user()->unit->id, [
            'nama' => $this->namaBank,
            'alamat' => $this->alamatBank,
            'jam_buka' => $this->jamBuka,
            'jam_tutup' => $this->jamTutup,
            'telepon' => $this->nomorTelepon,
            'hari_buka' => $this->hariBuka,
            'kode_bank' => $this->kodeBank,
        ]);

        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    public function editProfile()
    {
        $this->validate([
            'namaLengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string'],
            'nomorTeleponNasabah' => ['required', 'regex:/^08\d{8,}$/', Rule::unique('users', 'nomor_hp')->ignore(Auth::user()->id)],
            'fotoProfile' => ['nullable', 'image', 'max:2048'],
        ]);

        $this->userService->updateProfile(Auth::user()->id, [
            'nama' => $this->namaLengkap,
            'email' => $this->email,
            'nomor_hp' => $this->nomorTeleponNasabah,
            'avatar' => $this->fotoProfile,
        ]);
        $this->reset(['namaLengkap', 'email', 'nomorTeleponNasabah', 'fotoProfile']);
        $this->dispatch('close-modal');
        $this->alertPopUp();
    }

    public function editPassword()
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);
        $this->userService->updatePassword(Auth::user()->id, $this->password);
        $this->reset(['password']);
        $this->dispatch('close-modal');
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
    {{-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant --}}

    {{-- ======= MOBILE ======= --}}
    @include('panel.profile.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.profile.⚡dekstop-mode')
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-edit-profile').modal('hide');
                $('#wm-edit-passwor').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript

</div>
