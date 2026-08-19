<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Models\Organisasi;
use App\Models\BankSampah;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Livewire\TraitComponent;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NasabahImportSheets;
use App\Exports\NasabahExport;
new class extends Component {
    use WithPagination;
    use TraitComponent;
    use WithFileUploads;
    protected UserServices $userService;

    public ?int $perPage = 10;

    //Properti untuk search
    public ?string $keyword = '';

    // Properti untuk form pendaftaran nasabah
    public ?string $nama = '';
    public ?string $nik = '';
    public ?string $nomorTelepon = '';
    public ?string $email = '';
    public $jenis = 'perorangan';
    public ?int $organisasi = null;
    public ?string $password = '';
    public ?int $unit = null;

    // Properti detail nasabah
    public int $nasabahId;
    public ?string $namaNasabah = '';
    public ?string $nikNasabah = '';
    public ?string $nomorTeleponNasabah = '';
    public ?string $emailNasabah = '';
    public $jenisNasabah = 'perorangan';
    public ?int $organisasiNasabah = null;
    public ?string $passwordNasabah = '';
    public ?string $statusNasabah = '';
    public ?string $unitNasabah = '';
    public ?int $saldoNasabah = 0;
    public ?int $totalSetoran = 0;
    public ?int $totalBeratSetoran = 0;
    public ?string $tglDaftar = '';
    public ?string $tglLastSetor = '';
    public ?string $tglLastWd = '';
    public ?string $userId = '';
    public ?string $namaUnitNasabah = '';
    public bool $isAdmin = false;

    public array $bukuTabungan = [];
    public int $unitBukuTabungan;

    public bool $lock = false;

    public $file = '';

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function mount()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $this->unitNasabah = $user->bank_sampah_id;
            $this->lock = true;
        }
    }
    public function downloadFormat()
    {
        $path = public_path('assets/file/format_import_nasabah.xlsx');
        return response()->download($path);
    }
    public function exportNasabah()
    {
        return Excel::download(new NasabahExport(), 'nasabah.xlsx');
    }
    public function uploadFile()
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:2048'],
        ]);

        try {
            $import = new NasabahImportSheets();
            Excel::import($import, $this->file->getRealPath());

            if ($import->failures()->isNotEmpty()) {
                $rows = $import->failures()->map(fn($f) => 'Baris ' . $f->row() . ': ' . implode(', ', $f->errors()))->toArray();
                session()->flash('import_errors', $rows);
            } else {
                Log::info('Import nasabah berhasil selesai', [
                    'file' => $this->file->getClientOriginalName(),
                ]);
                session()->flash('success', 'File Excel berhasil diimport.');
                $this->dispatch('close-modal');
            }

            $this->reset('file');
        } catch (\Throwable $e) {
            Log::error('Import nasabah gagal total', ['error' => $e->getMessage()]);
            session()->flash('error', 'Gagal import: ' . $e->getMessage());
        }

        $this->alertPopUp();
    }
    public function loadPerpage()
    {
        $this->perPage += 10;
    }
    public function updatedKeyword()
    {
        $this->resetPage();
    }
    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }

    public function detail(string $id)
    {
        $id = decrypt($id);
        $user = $this->userService->getUserById($id);
        $this->nasabahId = $user->id;
        $this->namaNasabah = $user->name;
        $this->nikNasabah = $user->nik;
        $this->nomorTeleponNasabah = $user->nomor_hp;
        $this->emailNasabah = $user->email;
        $this->jenisNasabah = $user->mewakili;
        $this->organisasiNasabah = $user->organisasi_id;
        $this->passwordNasabah = $user->password;
        $this->unitNasabah = $user->bank_sampah_id;
        $this->statusNasabah = $user->status;
        $this->saldoNasabah = $user->bukutabungans->sum('saldo');
        $this->totalBeratSetoran = $user->setorans->sum('total_berat');
        $this->totalSetoran = $user->setorans->count();
        $this->tglDaftar = $user->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i');
        $this->tglLastSetor = $user->setorans()->latest()->value('created_at')
            ? \Carbon\Carbon::parse($user->setorans()->latest()->value('created_at'))
                ->timezone('Asia/Jakarta')
                ->format('d M Y, H:i')
            : 'Tidak Ada';
        $this->tglLastWd = $user->transaksis()->latest()->value('created_at')
            ? \Carbon\Carbon::parse($user->transaksis()->latest()->value('created_at'))
                ->timezone('Asia/Jakarta')
                ->format('d M Y, H:i')
            : 'Tidak Ada';
        $this->userId = $user->id;
        $this->namaUnitNasabah = $user->unit->nama;
        $this->isAdmin = $user->hasRole('admin');
    }

    public function editNasabah()
    {
        $rules = [
            'namaNasabah' => 'required|string|max:120',
            'nikNasabah' => [
                'required',
                'digits:16',
                function ($attribute, $value, $fail) {
                    $exists = $this->userService->userBuilder()->where('nik_hash', hash('sha256', $value))->where('id', '!=', $this->nasabahId)->exists();
                    if ($exists) {
                        $fail('NIK sudah terdaftar.');
                    }
                },
            ],
            'nomorTeleponNasabah' => ['required', 'regex:/^08\d{8,}$/', Rule::unique('users', 'nomor_hp')->ignore($this->nasabahId)],
            'emailNasabah' => 'required|email',
            'jenisNasabah' => 'required|in:perorangan,kelompok',
            'organisasiNasabah' => $this->jenis == 'perorangan' ? 'nullable' : 'required|exists:organisasis,id',
            'unitNasabah' => 'required|exists:bank_sampahs,id',
        ];
        $this->validate($rules);
        $this->userService->updateUser($this->nasabahId, [
            'name' => $this->namaNasabah,
            'nik' => $this->nikNasabah,
            'nomor_hp' => $this->nomorTeleponNasabah,
            'email' => $this->emailNasabah,
            'mewakili' => $this->jenisNasabah,
            'organisasi_id' => $this->organisasiNasabah,
            'bank_sampah_id' => $this->unitNasabah,
            'is_admin' => $this->isAdmin,
        ]);
        $this->reset(['namaNasabah', 'nikNasabah', 'nomorTeleponNasabah', 'emailNasabah', 'jenisNasabah', 'organisasiNasabah', 'unitNasabah']);
        $this->dispatch('close-modal');
        $this->alert();
    }

    public function registerNasabah()
    {
        $unit = Auth::user()->unit->id;
        $parent = Auth::user()->unit->parent_id;
        $rules = [
            'nama' => 'required',
            'nik' => [
                'required',
                'digits:16',
                function ($attribute, $value, $fail) {
                    $exists = $this->userService->userBuilder()->where('nik_hash', hash('sha256', $value))->exists();
                    if ($exists) {
                        $fail('NIK sudah terdaftar.');
                    }
                },
            ],
            'nomorTelepon' => 'required|regex:/^08\d{8,}$/|unique:users,nomor_hp',
            'email' => 'required|email',
            'jenis' => 'required|in:perorangan,kelompok',
            'organisasi' => $this->jenis == 'perorangan' ? 'nullable' : 'required|exists:organisasis,id',
            'password' => 'required|min:6',
            'unit' => 'required|exists:bank_sampahs,id',
        ];
        if ($parent) {
            $this->unit = $unit;
        }
        $this->validate($rules);
        $this->userService->register([
            'name' => $this->nama,
            'nik' => $this->nik,
            'nomor_hp' => $this->nomorTelepon,
            'email' => $this->email,
            'mewakili' => $this->jenis,
            'organisasi_id' => $this->organisasi,
            'password' => $this->password,
            'bank_sampah_id' => $this->unit,
        ]);
        $this->reset(['nama', 'nik', 'nomorTelepon', 'email', 'jenis', 'organisasi', 'password']);
        $this->dispatch('close-modal');
        $this->alert();
    }

    public function updatedJenis($value)
    {
        if ($value === 'perorangan') {
            $this->organisasi = null;
        }
    }

    public function getBukuTabungan(string $nasabahId)
    {
        $nasabahId = decrypt($nasabahId);
        $this->nasabahId = $nasabahId;
        $data = $this->userService->getBukuTabunganByUserId($nasabahId);
        $this->bukuTabungan = $data->toArray();
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

    public function getData()
    {
        $parent = Auth::user()->unit->parent_id;
        $unit = Auth::user()->unit->id;
        $user = $this->userService->userBuilder();
        $nasabahQuery = $user->with(['organisasi', 'bukutabungans', 'setorans']);
        $totalNasabah = $this->userService->totalNasabah();

        if ($this->keyword) {
            $nasabahQuery->where(function ($q) {
                $q->where('name', 'like', "%{$this->keyword}%")
                    ->orWhere('nik_hash', hash('sha256', $this->keyword))
                    ->orWhereHas('unit', function ($q2) {
                        $q2->where('nama', 'like', "%{$this->keyword}%");
                    })
                    ->orWhereHas('bukutabungans', function ($q2) {
                        $q2->where('nomor_rekening', 'like', "%{$this->keyword}%");
                    });
            });
        }
        if ($parent) {
            $nasabahQuery
                ->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['supervisor', 'admin']);
                })
                ->where(function ($query) use ($unit) {
                    $query
                        ->whereHas('unit', function ($q) use ($unit) {
                            $q->where('id', $unit);
                        })
                        ->orWhereHas('bukutabungans', function ($q) use ($unit) {
                            $q->where('bank_id', $unit);
                        });
                });
        }
        $nasabah = $nasabahQuery->latest()->paginate($this->perPage);
        $roles = Role::whereNotIn('name', ['supervisor'])->get();
        return [
            'totalNasabah' => $totalNasabah,
            'organisasi' => Organisasi::query()->get(),
            'banksampah' => BankSampah::query()->get(),
            'nasabah' => $nasabah,
            'roles' => $roles,
        ];
    }
    #[On('doDelete')]
    public function delete(string $userId)
    {
        $this->userId = decrypt($userId);
        $this->userService->deleteUser($this->userId);
        $this->alert();
    }

    public function alertDelete(string $userId)
    {
        $this->userId = decrypt($userId);
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
    @php $data = $this->getData(); @endphp

    {{-- ======= MOBILE ======= --}}
    @include('panel.nasabah.⚡mobile-mode')

    {{-- ======= DESKTOP ======= --}}
    @include('panel.nasabah.⚡dekstop-mode')

    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-tambah-nasabah').modal('hide');
                $('#wm-edit-nasabah').modal('hide');
                $('#wm-rekening-nasabah').modal('hide');
                $('#wm-import-nasabah').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
