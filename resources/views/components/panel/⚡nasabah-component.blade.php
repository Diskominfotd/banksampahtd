<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Models\Organisasi;

new class extends Component {
    protected UserServices $userService;
    public ?string $nama = '';
    public ?string $nik = '';
    public ?string $nomorHp = '';
    public ?string $email = '';
    public $jenis = '0';
    public ?int $organisasi = null;
    public ?string $rekening = '';

    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function registerNasabah()
    {
        $rules = [
            'nama' => 'required',
            'nik' => 'required|digits:16',
            'nomorHp' => 'required|regex:/^08\d{8,}$/',
            'email' => 'required|email',
            'jenis' => 'required|in:0,1',
            'rekening' => 'required',
        ];

        if ($this->jenis === 1) {
            $rules['organisasi'] = 'required|exists:organisasi,id';
        } else {
            $rules['organisasi'] = 'nullable';
        }

        $this->validate($rules);
    }

    public function getData()
    {
        return [
            'organisasi' => Organisasi::query()->get(),
        ];
    }
};
?>

<div>
    {{-- Order your soul. Reduce your wants. - Augustine --}}
    <div wire:ignore.self class="modal fade" id="wm-tambah-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Daftarkan Nasabah Baru</div>
                    <div class="w-modal-close" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </div>
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
                                    <input class="w-form-input" type="tel" placeholder="08xx-xxxx-xxxx"
                                        wire:model="nomorHp">
                                    @error('nomorHp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Email</label>
                                    <input class="w-form-input" type="email" name="email"
                                        placeholder="email@gamil.com" wire:model="email">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Jenis Nasabah</label>
                                    <select class="w-form-input" wire:model.live="jenis">
                                        <option value="0">Perorangan</option>
                                        <option value="1">Kelompok</option>
                                    </select>
                                    @error('jenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Organisasi</label>
                                    <select class="w-form-input" wire:model="organisasi" @disabled($jenis === 0)>
                                        <option value="">Pilih Organisasi</option>
                                        @foreach ($this->getData()['organisasi'] as $org)
                                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                        @endforeach
                                    </select>

                                    @error('organisasi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="w-form-label">No. Rekening</label>
                                <input class="w-form-input" type="text" wire:model="rekening"
                                    placeholder="Nomor rekening bank sampah" wire:model="rekening">
                                @error('rekening')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary">Daftarkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-detail-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </div>
                </div>
                <div class="w-modal-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar" style="width:52px;height:52px;font-size:18px">SR</div>
                        <div>
                            <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700">Siti Rahayu</div>
                            <div style="font-size:11px;color:var(--muted)">No. Nasabah: BS-001 · Unit Sukajadi</div>
                        </div>
                        <span class="bs bs-green ms-auto">Aktif</span>
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
                                    Rp 380.000</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div
                                style="background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;padding:14px;text-align:center">
                                <div
                                    style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px">
                                    Total Setoran</div>
                                <div style="font-family:'Syne',sans-serif;font-size:24px;font-weight:700">87 kg / 47
                                    trx
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">No. HP</span><span
                                    class="df-val">0812-3456-7890</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">Bergabung</span><span class="df-val">12
                                    Maret
                                    2024</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field"><span class="df-key">Terakhir Setor</span><span
                                    class="df-val">29
                                    Mei 2026</span></div>
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
    <div class="desktop-wrapper">
        @include('panel.template.dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Dashboard Pengelola</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search"><i class="bi bi-search si"></i><input type="text"
                            placeholder="Cari nasabah, setoran..."></div>
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
                        <button class="w-btn w-btn-ghost" style="font-size:11px"><i
                                class="bi bi-download me-1"></i>Export</button>
                        <button class="w-btn w-btn-primary" style="font-size:11px"data-bs-toggle="modal"
                            data-bs-target="#wm-tambah-nasabah"><i class="bi bi-person-plus me-1"></i>Tambah
                            Nasabah</button>
                    </div>
                </div>
                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%"><i class="bi bi-search si"></i><input
                            type="text" placeholder="Cari nama nasabah, unit..." style="width:100%"></div>
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
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">BS-001</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar" style="width:28px;height:28px;font-size:10px">SR</div>
                                        <div style="font-size:11px;font-weight:600">Siti Rahayu</div>
                                    </div>
                                </td>
                                <td><span class="bs bs-ok">Perorangan</span></td>
                                <td style="font-size:10px;color:var(--muted)">Sukajadi</td>
                                <td style="font-weight:600">87 kg / 47 trx</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp380.000</td>
                                <td><span class="bs bs-green">Aktif</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        data-bs-toggle="modal" data-bs-target="#wm-detail-nasabah">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
