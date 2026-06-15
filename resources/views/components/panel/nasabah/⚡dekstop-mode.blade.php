<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Simplicity is the ultimate sophistication. - Leonardo da Vinci --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
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
                        <input wire:model.live="keyword" type="text" placeholder="Cari nama nasabah, unit..."
                            style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Unit Pendaftaran</th>
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
                                            data-bs-toggle="modal" data-bs-target="#wm-edit-nasabah">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="detail('{{ encrypt($nasabah->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-nasabah">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button wire:click="alertDelete('{{ encrypt($nasabah->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <button data-bs-toggle="modal" data-bs-target="#wm-rekening-nasabah"
                                            wire:click="getBukuTabungan('{{ encrypt($nasabah->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                            Rekening
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['nasabah']->links('vendor.pagination.bootstrap-5') }}
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
                    <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
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

    {{-- ======= MODAL DESKTOP: EDIT NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-edit-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Edit Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editNasabah">
                    <div class="w-modal-body">
                        <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                            <div class="spinner-border text-success"></div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Nama Lengkap / Nama Usaha</label>
                                    <input class="w-form-input" type="text" wire:model="namaNasabah">
                                    @error('namaNasabah')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">NIK</label>
                                    <input class="w-form-input" type="text" wire:model="nikNasabah"
                                        placeholder="16 digit NIK KTP" maxlength="16">
                                    @error('nikNasabah')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Email</label>
                                    <input class="w-form-input" type="email" wire:model="emailNasabah">
                                    @error('emailNasabah')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Jenis Nasabah</label>
                                    <select class="w-form-input" wire:model.live="jenisNasabah">
                                        <option value="perorangan">Perorangan</option>
                                        <option value="kelompok">Kelompok</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Organisasi</label>
                                    <select class="w-form-input" wire:model="organisasiNasabah"
                                        @disabled($jenisNasabah === 'perorangan')>
                                        <option value="">Pilih Organisasi</option>
                                        @foreach ($data['organisasi'] as $org)
                                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('editOrganisasi')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Unit</label>
                                    <select class="w-form-input" wire:model="unitNasabah">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($data['banksampah'] as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('unitNasabah')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">No. HP</label>
                                    <input class="w-form-input" type="tel" wire:model="nomorHpNasabah">
                                    @error('nomorHpNasabah')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editNasabah">
                            <span wire:loading.remove wire:target="editNasabah">Simpan</span>
                            <span wire:loading wire:target="editNasabah">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ======= MODAL DESKTOP: REKENING NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-rekening-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Rekening Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body">
                    <form wire:submit="addBukuTabungan">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="w-form-label">Unit</label>
                                    <select class="w-form-input" wire:model="unitBukuTabungan">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($data['banksampah'] as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('unitBukuTabungan')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                                wire:target="addBukuTabungan">
                                <span wire:loading.remove wire:target="addBukuTabungan">Tambahkan</span>
                                <span wire:loading wire:target="addBukuTabungan">Loading...</span>
                            </button>
                        </div>
                    </form>
                    @if (!empty($bukuTabungan))
                        <div class="mt-3">
                            <label class="w-form-label mb-2">Daftar Buku Tabungan</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($bukuTabungan as $bk)
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2"
                                        style="border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                                        <span style="font-size: 13px; font-weight: 500; color: #111827;">
                                            {{ ucfirst($bk['nama']) }}
                                        </span>
                                        <span style="font-size: 12px; color: #6b7280; font-family: monospace;">
                                            {{ $bk['nomor_rekening'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-3 text-center" style="font-size: 13px; color: #9ca3af; padding: 12px 0;">
                            Belum ada buku tabungan
                        </div>
                    @endif

                </div>
                <div class="w-modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>
