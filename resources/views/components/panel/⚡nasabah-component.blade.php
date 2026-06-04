<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Models\Organisasi;
use App\Models\BankSampah;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    protected UserServices $userService;
    
    // Properti untuk form pendaftaran nasabah
    public ?string $nama = '';
    public ?string $nik = '';
    public ?string $nomorHp = '';
    public ?string $email = '';
    public $jenis = 'perorangan';
    public ?int $organisasi = null;
    public ?string $rekening = '';
    public ?string $password = '';
    public ?int $unit = null;

    // Properti detail nasabah
    public ?string $namaNasabah = '';
    public ?string $nikNasabah = '';
    public ?string $nomorHpNasabah = '';
    public ?string $emailNasabah = '';
    public $jenisNasabah = 'perorangan';
    public ?int $organisasiNasabah = null;
    public ?string $rekeningNasabah = '';
    public ?string $passwordNasabah = '';
    public ?string $statusNasabah = '';
    public ?string $unitNasabah = '';

    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function detail(string $id)
    {
        $id = decrypt($id);
        $user = $this->userService->getUserById($id);
        $this->namaNasabah = $user->name;
        $this->nikNasabah = $user->nik;
        $this->nomorHpNasabah = $user->nomor_hp;
        $this->emailNasabah = $user->email;
        $this->jenisNasabah = $user->mewakili;
        $this->organisasiNasabah = $user->organisasi_id;
        $this->rekeningNasabah = $user->rekening;
        $this->passwordNasabah = $user->password;
        $this->unitNasabah = $user->unit->nama;
    }

    public function registerNasabah()
    {
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
            'nomorHp' => 'required|regex:/^08\d{8,}$/',
            'email' => 'required|email',
            'jenis' => 'required|in:perorangan,kelompok',
            'rekening' => 'required|string',
            'organisasi' => $this->jenis == 'perorangan' ? 'nullable' : 'required|exists:organisasis,id',
            'password' => 'required|min:6',
            'unit' => 'required|exists:bank_sampahs,id',
        ];
        $this->validate($rules);
        $this->userService->register([
            'name' => $this->nama,
            'nik' => $this->nik,
            'nomor_hp' => $this->nomorHp,
            'email' => $this->email,
            'mewakili' => $this->jenis,
            'organisasi_id' => $this->organisasi,
            'password' => $this->password,
            'rekening' => $this->rekening,
            'bank_sampah_id' => $this->unit,
        ]);
        $this->reset(['nama', 'nik', 'nomorHp', 'email', 'jenis', 'organisasi', 'rekening', 'password']);
        $this->dispatch('close-modal');
    }

    public function updatedJenis($value)
    {
        if ($value === 'perorangan') {
            $this->organisasi = null;
        }
    }

    public function getData()
    {
        $user = $this->userService->userBuilder();
        $nasabah = $user->with('organisasi')->latest()->paginate(10);
        return [
            'organisasi' => Organisasi::query()->get(),
            'banksampah' => BankSampah::query()->get(),
            'nasabah' => $nasabah,
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
    @php $data = $this->getData(); @endphp

    {{-- ======= MOBILE ======= --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" onclick="mNav('m-beranda')">
                <i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Data Nasabah</div>
            <div class="ms-auto">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-nasabah')">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
        </div>

        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" placeholder="Cari nama nasabah, unit...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['nasabah'] as $index => $nasabah)
                    <div class="list-item fade-up">
                        <span class="list-num">{{ $index + 1 }}</span>
                        <div class="avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                            {{ strtoupper($nasabah->initials()) }}
                        </div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($nasabah->name) }}</div>
                            <div class="list-sub">
                                {{ $nasabah->organisasi->nama ?? '-' }} · 0 setoran · Saldo Rp 0
                            </div>
                        </div>
                        <span class="bs bs-ok">Aktif</span>
                    </div>
                @endforeach
            </div>
        </div>

        @include('panel.template.mobile-bottombar')
    </div>

    {{-- ======= BOTTOM SHEET MOBILE — backdrop ======= --}}
    <div x-show="$store.sheet.is('tambah-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('tambah-nasabah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>

        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Daftarkan Nasabah Baru
        </div>

        <form wire:submit="registerNasabah">
            <div class="f-group">
                <label>Nama Lengkap / Nama Usaha</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="Nama nasabah atau badan usaha">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>NIK</label>
                <input class="f-input" type="text" wire:model="nik" placeholder="16 digit NIK KTP" maxlength="16">
                @error('nik')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>No. HP</label>
                <input class="f-input" type="tel" wire:model="nomorHp" placeholder="08xx-xxxx-xxxx">
                @error('nomorHp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>Email</label>
                <input class="f-input" type="email" wire:model="email" placeholder="email@gmail.com">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>Jenis Nasabah</label>
                <select class="f-input" wire:model.live="jenis">
                    <option value="perorangan">Perorangan</option>
                    <option value="kelompok">Kelompok</option>
                </select>
                @error('jenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>Organisasi</label>
                <select class="f-input" wire:model="organisasi" @disabled($jenis === 'perorangan')>
                    <option value="">Pilih Organisasi</option>
                    @foreach ($data['organisasi'] as $org)
                        <option value="{{ $org->id }}">{{ $org->nama }}</option>
                    @endforeach
                </select>
                @error('organisasi')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Unit</label>
                <select class="f-input" wire:model="unit">
                    <option value="">Pilih Unit</option>
                    @foreach ($data['banksampah'] as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                    @endforeach
                </select>
                @error('unit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group">
                <label>No. Rekening</label>
                <input class="f-input" type="text" wire:model="rekening" placeholder="Nomor rekening bank sampah">
                @error('rekening')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="f-group" x-data="{ show: false }">
                <label>Password</label>
                <div style="position:relative">
                    <input class="f-input" :type="show ? 'text' : 'password'" wire:model="password"
                        placeholder="Password nasabah" style="padding-right:40px">
                    <button type="button" @click="show = !show"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted)">
                        <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-primary mb-2" style="width:100%" wire:loading.attr="disabled"
                wire:target="registerNasabah">
                <span wire:loading.remove wire:target="registerNasabah">
                    <i class="bi bi-person-plus me-1"></i>Daftarkan Nasabah
                </span>
                <span wire:loading wire:target="registerNasabah">Loading...</span>
            </button>
            <button type="button" class="btn-outline" style="width:100%"
                @click="$store.sheet.hide()">Batal</button>
        </form>
    </div>

    {{-- ======= DESKTOP ======= --}}
    <div class="desktop-wrapper">
        @include('panel.template.dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Dashboard Pengelola</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search"><i class="bi bi-search si"></i>
                        <input type="text" placeholder="Cari nasabah, setoran...">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end">
                            <div class="w-uname">Budi Santoso</div>
                            <div class="w-urole">Pengelola Bank Sampah</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>

            <div id="w-nasabah" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Data Nasabah</div>
                        <div style="font-size:11px;color:var(--muted)">128 nasabah aktif — 3 nasabah baru bulan ini
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="w-btn w-btn-ghost" style="font-size:11px">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                            data-bs-target="#wm-tambah-nasabah">
                            <i class="bi bi-person-plus me-1"></i>Tambah Nasabah
                        </button>
                    </div>
                </div>

                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%">
                        <i class="bi bi-search si"></i>
                        <input type="text" placeholder="Cari nama nasabah, unit..." style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Unit</th>
                                <th>Total Setoran</th>
                                <th>Saldo</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['nasabah'] as $index => $nasabah)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar" style="width:28px;height:28px;font-size:10px">
                                                {{ strtoupper($nasabah->initials()) }}
                                            </div>
                                            <div style="font-size:11px;font-weight:600">
                                                {{ ucfirst($nasabah->name) ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="bs bs-ok">
                                            {{ strtoupper($nasabah->mewakili) ?? '-' }}
                                        </span>
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ $nasabah->unit->nama ?? '-' }}
                                    </td>
                                    <td style="font-weight:600">0 kg / 0 trx</td>
                                    <td style="font-weight:700;color:var(--cyan)">Rp 0</td>
                                    <td><span class="bs bs-green">Aktif</span></td>
                                    <td>
                                        <button wire:click="detail('{{ encrypt($nasabah->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-nasabah">
                                            Detail
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ======= MODAL DESKTOP: TAMBAH NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-tambah-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Daftarkan Nasabah Baru</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="registerNasabah">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Nama Lengkap / Nama Usaha</label>
                                    <input class="w-form-input" type="text" wire:model="nama"
                                        placeholder="Nama nasabah atau badan usaha">
                                    @error('nama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">NIK</label>
                                    <input class="w-form-input" type="text" wire:model="nik"
                                        placeholder="16 digit NIK KTP" maxlength="16">
                                    @error('nik')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">No. HP</label>
                                    <input class="w-form-input" type="tel" wire:model="nomorHp"
                                        placeholder="08xx-xxxx-xxxx">
                                    @error('nomorHp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Email</label>
                                    <input class="w-form-input" type="email" wire:model="email"
                                        placeholder="email@gmail.com">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Jenis Nasabah</label>
                                    <select class="w-form-input" wire:model.live="jenis">
                                        <option value="perorangan">Perorangan</option>
                                        <option value="kelompok">Kelompok</option>
                                    </select>
                                    @error('jenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Organisasi</label>
                                    <select class="w-form-input" wire:model.live="organisasi"
                                        @disabled($jenis === 'perorangan')>
                                        <option value="">Pilih Organisasi</option>
                                        @foreach ($data['organisasi'] as $org)
                                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('organisasi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Unit</label>
                                    <select class="w-form-input" wire:model.live="unit">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($data['banksampah'] as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">No. Rekening</label>
                                    <input class="w-form-input" type="text" wire:model="rekening"
                                        placeholder="Nomor rekening bank sampah">
                                    @error('rekening')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6" x-data="{ show: false }">
                                    <label class="w-form-label">Password</label>
                                    <div style="position:relative">
                                        <input class="w-form-input" :type="show ? 'text' : 'password'"
                                            wire:model="password" placeholder="Password nasabah"
                                            style="padding-right:40px">
                                        <button type="button" @click="show = !show"
                                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted)">
                                            <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="registerNasabah">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="registerNasabah">
                            <span wire:loading.remove wire:target="registerNasabah">Daftarkan</span>
                            <span wire:loading wire:target="registerNasabah">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======= MODAL DESKTOP: DETAIL NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-detail-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar" style="width:52px;height:52px;font-size:18px">SR</div>
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700">
                                {{ ucfirst($this->namaNasabah) }}</div>
                            <div style="font-size:11px;color:var(--muted)">Unit - {{ $this->unitNasabah }}
                            </div>
                        </div>
                        <span class="bs bs-green ms-auto">{{ $this->statusNasabah }}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div
                                style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:12px;padding:14px;text-align:center">
                                <div
                                    style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px">
                                    Saldo Tabungan</div>
                                <div
                                    style="font-family:'Syne',sans-serif;font-size:24px;font-weight:700;color:var(--cyan)">
                                    Rp 0</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div
                                style="background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;padding:14px;text-align:center">
                                <div
                                    style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px">
                                    Total Setoran</div>
                                <div style="font-family:'Syne',sans-serif;font-size:24px;font-weight:700">0 kg / 0
                                    trx</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">No. HP</span><span
                                    class="df-val">{{ $this->nomorHpNasabah }}</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">Bergabung</span><span class="df-val">12
                                    Maret 2024</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">Terakhir Setor</span><span
                                    class="df-val">29 Mei 2026</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">Jenis Sampah Utama</span><span
                                    class="df-val">Plastik HDPE</span></div>
                        </div>
                    </div>
                </div>
                <div class="w-modal-footer">
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                        onclick="setTimeout(()=> new bootstrap.Modal(document.getElementById('wm-tambah-setoran')).show(), 300)">
                        Catat Setoran
                    </button>
                    <button class="w-btn w-btn-danger">Proses Penarikan</button>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('close-modal', () => {
                // Tutup modal desktop
                const el = document.getElementById('wm-tambah-nasabah');
                if (el) bootstrap.Modal.getInstance(el)?.hide();

                // Tutup bottom sheet mobile
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
