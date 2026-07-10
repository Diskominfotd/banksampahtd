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
            @include('components.⚡dekstop-header')
            <div id="w-nasabah" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Data Nasabah</div>
                        <div style="font-size:11px;color:var(--muted)">128 nasabah aktif — 3 nasabah baru bulan ini
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        {{-- <button class="w-btn w-btn-ghost" style="font-size:11px">
                            <i class="bi bi-download me-1"></i>Export
                        </button> --}}
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
                                <th>Level</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['nasabah'] as $index => $nasabah)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ $data['nasabah']->firstItem() + $index }}
                                    </td>
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
                                    <td style="font-weight:600">{{ $nasabah->setorans->sum('total_berat') }} kg /
                                        {{ $nasabah->setorans->count() }} trx</td>
                                    <td style="font-weight:700;color:var(--cyan)">Rp
                                        {{ number_format($nasabah->bukutabungans->sum('saldo'), 0, ',', '.') }}
                                    </td>
                                    <td><span
                                            class="bs bs-green">{{ ucfirst($nasabah->getRoleNames()->first()) }}</span>
                                    </td>
                                    <td><span class="bs bs-green">{{ ucfirst($nasabah->status) }}</span></td>
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
                  <div class="mt-2">
                      {{ $data['nasabah']->links() }}
                  </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======= MODAL DESKTOP: TAMBAH NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-tambah-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                    <div x-data="{
                                        search: '',
                                        open: false,
                                        selected: '',
                                        selectedLabel: 'Pilih Unit',
                                        all: {{ Js::from($data['banksampah']) }},
                                        get filtered() {
                                            if (this.search === '') return this.all.slice(0, 10);
                                            return this.all.filter(o => o.nama.toLowerCase().includes(this.search.toLowerCase())).slice(0, 10);
                                        },
                                        select(id, label) {
                                            this.selected = id;
                                            this.selectedLabel = label;
                                            this.open = false;
                                            this.search = '';
                                            $wire.set('unit', id);
                                        }
                                    }" x-on:click.outside="open = false"
                                        class="position-relative">

                                        {{-- Trigger --}}
                                        <button type="button" x-on:click="open = !open"
                                            class="form-select text-start d-flex align-items-center justify-content-between w-100"
                                            style="height: 42px; border-radius: 8px; border: 1.5px solid #dee2e6; background: #fff; transition: border-color .2s;"
                                            :style="open ?
                                                'border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.12);' :
                                                ''">
                                            <span
                                                :class="selected === '' ? 'text-muted' : 'text-dark fw-medium'"
                                                style="font-size: 14px;" x-text="selectedLabel"></span>
                                            <i class="ti ti-chevron-down text-muted"
                                                style="font-size: 15px; transition: transform .2s;"
                                                :style="open ? 'transform: rotate(180deg)' : ''"></i>
                                        </button>

                                        {{-- Dropdown (ke ATAS) --}}
                                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-100"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            style="position: absolute; bottom: calc(100% + 6px); left: 0; right: 0; z-index: 1050;
                                            background: #fff; border: 1.5px solid #dee2e6;
                                            border-radius: 10px; overflow: hidden;
                                            box-shadow: 0 -4px 24px rgba(0,0,0,.08), 0 -1px 6px rgba(0,0,0,.04);">

                                            {{-- Search box --}}
                                            <div style="padding: 10px 10px 6px;">
                                                <div class="position-relative">
                                                    <i class="bi bi-search position-absolute text-muted"
                                                        style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                                                    <input type="text" x-model="search" x-on:click.stop
                                                        placeholder="Cari unit..." class="form-control"
                                                        style="padding-left: 34px; font-size: 13px; border-radius: 6px;
                                                        border: 1.5px solid #e9ecef; background: #f8f9fa; height: 36px;">
                                                </div>
                                            </div>

                                            <div style="height: 1px; background: #f0f0f0; margin: 0 10px;"></div>

                                            {{-- Options --}}
                                            <div style="max-height: 180px; overflow-y: auto; padding: 6px;">
                                                <template x-if="filtered.length === 0">
                                                    <div class="text-center text-muted py-3" style="font-size: 13px;">
                                                        <i class="bi bi-inbox d-block mb-1"
                                                            style="font-size: 22px;"></i>
                                                        Tidak ditemukan
                                                    </div>
                                                </template>
                                                <template x-for="option in filtered" :key="option.id">
                                                    <div x-on:click="select(option.id, option.nama)"
                                                        style="padding: 8px 10px; font-size: 13px; border-radius: 6px;
                                                        cursor: pointer; transition: background .15s; display: flex;
                                                        align-items: center; justify-content: space-between;"
                                                        :style="selected == option.id ?
                                                            'background: #e7f0ff; color: #0d6efd; font-weight: 500;' :
                                                            'color: #212529;'"
                                                        x-on:mouseenter="if(selected != option.id) $el.style.background = '#f8f9fa'"
                                                        x-on:mouseleave="if(selected != option.id) $el.style.background = 'transparent'">
                                                        <span x-text="option.nama"></span>
                                                        <i class="ti ti-check" style="font-size: 14px;"
                                                            x-show="selected == option.id"></i>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- Footer count --}}
                                            <div style="padding: 6px 12px 8px; border-top: 1px solid #f0f0f0;">
                                                <small class="text-muted" style="font-size: 11px;"
                                                    x-text="`Menampilkan ${filtered.length} dari ${all.length} unit`"></small>
                                            </div>
                                        </div>

                                        <input type="hidden" wire:model="unit" :value="selected">
                                    </div>

                                    @error('unit')
                                        <small class="text-danger mt-1 d-block">
                                            <i class="ti ti-alert-circle me-1"></i>{{ $message }}
                                        </small>
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
                                {{ ucfirst($this->namaNasabah) }} - {{ $this->nikNasabah }}</div>
                            <div style="font-size:11px;color:var(--muted)">Unit - {{ $this->unitNasabah }}
                            </div>
                        </div>
                        <span class="bs bs-green ms-auto">{{ ucfirst($this->statusNasabah) }}</span>
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
                                    Rp {{ number_format($this->saldoNasabah, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div
                                style="background:var(--bg-deep);border:1px solid var(--border);border-radius:12px;padding:14px;text-align:center">
                                <div
                                    style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px">
                                    Total Setoran</div>
                                <div style="font-family:'Syne',sans-serif;font-size:24px;font-weight:700">
                                    {{ $this->totalBeratSetoran }} kg / {{ $this->totalSetoran }}
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
                            <div class="detail-field">
                                <span class="df-key">Bergabung</span>
                                <span class="df-val">{{ $tglDaftar }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field">
                                <span class="df-key">Terakhir Setor</span>
                                <span class="df-val">{{ $tglLastSetor }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field">
                                <span class="df-key">Terakir Tarik Dana</span>
                                <span class="df-val">{{ $tglLastWd }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-modal-footer">
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal" wire:click="movePage('setoran.catat')">
                        Catat Setoran
                    </button>
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                        wire:click="movePage('buat.penarikan.saldo')">
                        Buat Penarikan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ======= MODAL DESKTOP: EDIT NASABAH ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-edit-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Edit Nasabah {{ $unitNasabah }}</div>
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
                                    <label class="w-form-label">Unit - {{ $namaUnitNasabah ?? '-' }}</label>
                                    <div x-data="{
                                        search: '',
                                        open: false,
                                        selected: $wire.entangle('unitNasabah'),
                                        selectedLabel: 'Pilih Unit',
                                        all: {{ Js::from($data['banksampah']) }},
                                        init() {
                                            if (this.selected) {
                                                const found = this.all.find(o => o.id == this.selected);
                                                if (found) this.selectedLabel = found.nama;
                                            }
                                        },
                                        get filtered() {
                                            if (this.search === '') return this.all.slice(0, 10);
                                            return this.all.filter(o => o.nama.toLowerCase().includes(this.search.toLowerCase())).slice(0, 10);
                                        },
                                        select(id, label) {
                                            this.selected = id;
                                            this.selectedLabel = label;
                                            this.open = false;
                                            this.search = '';
                                            $wire.set('unit', id);
                                        }
                                    }" x-on:click.outside="open = false"
                                        class="position-relative">

                                        {{-- Trigger --}}
                                        <button type="button" x-on:click="open = !open"
                                            class="form-select text-start d-flex align-items-center justify-content-between w-100"
                                            style="height: 42px; border-radius: 8px; border: 1.5px solid #dee2e6; background: #fff; transition: border-color .2s;"
                                            :style="open ?
                                                'border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.12);' :
                                                ''">
                                            <span
                                                :class="selected === '' ? 'text-muted' : 'text-dark fw-medium'"
                                                style="font-size: 14px;" x-text="selectedLabel"></span>
                                            <i class="ti ti-chevron-down text-muted"
                                                style="font-size: 15px; transition: transform .2s;"
                                                :style="open ? 'transform: rotate(180deg)' : ''"></i>
                                        </button>

                                        {{-- Dropdown (ke ATAS) --}}
                                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-100"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            style="position: absolute; bottom: calc(100% + 6px); left: 0; right: 0; z-index: 1050;
                                            background: #fff; border: 1.5px solid #dee2e6;
                                            border-radius: 10px; overflow: hidden;
                                            box-shadow: 0 -4px 24px rgba(0,0,0,.08), 0 -1px 6px rgba(0,0,0,.04);">

                                            {{-- Search box --}}
                                            <div style="padding: 10px 10px 6px;">
                                                <div class="position-relative">
                                                    <i class="bi bi-search position-absolute text-muted"
                                                        style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                                                    <input type="text" x-model="search" x-on:click.stop
                                                        placeholder="Cari unit..." class="form-control"
                                                        style="padding-left: 34px; font-size: 13px; border-radius: 6px;
                                                        border: 1.5px solid #e9ecef; background: #f8f9fa; height: 36px;">
                                                </div>
                                            </div>

                                            <div style="height: 1px; background: #f0f0f0; margin: 0 10px;"></div>

                                            {{-- Options --}}
                                            <div style="max-height: 180px; overflow-y: auto; padding: 6px;">
                                                <template x-if="filtered.length === 0">
                                                    <div class="text-center text-muted py-3" style="font-size: 13px;">
                                                        <i class="bi bi-inbox d-block mb-1"
                                                            style="font-size: 22px;"></i>
                                                        Tidak ditemukan
                                                    </div>
                                                </template>
                                                <template x-for="option in filtered" :key="option.id">
                                                    <div x-on:click="select(option.id, option.nama)"
                                                        style="padding: 8px 10px; font-size: 13px; border-radius: 6px;
                                                        cursor: pointer; transition: background .15s; display: flex;
                                                        align-items: center; justify-content: space-between;"
                                                        :style="selected == option.id ?
                                                            'background: #e7f0ff; color: #0d6efd; font-weight: 500;' :
                                                            'color: #212529;'"
                                                        x-on:mouseenter="if(selected != option.id) $el.style.background = '#f8f9fa'"
                                                        x-on:mouseleave="if(selected != option.id) $el.style.background = 'transparent'">
                                                        <span x-text="option.nama"></span>
                                                        <i class="ti ti-check" style="font-size: 14px;"
                                                            x-show="selected == option.id"></i>
                                                    </div>
                                                </template>
                                            </div>

                                            {{-- Footer count --}}
                                            <div style="padding: 6px 12px 8px; border-top: 1px solid #f0f0f0;">
                                                <small class="text-muted" style="font-size: 11px;"
                                                    x-text="`Menampilkan ${filtered.length} dari ${all.length} unit`"></small>
                                            </div>
                                        </div>

                                        <input type="hidden" wire:model="unitNasabah" :value="selected">
                                    </div>

                                    @error('unitNasabah')
                                        <small class="text-danger mt-1 d-block">
                                            <i class="ti ti-alert-circle me-1"></i>{{ $message }}
                                        </small>
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
                        {{-- {{ dd($bukuTabungan) }} --}}
                        <div class="mt-3">
                            <label class="w-form-label mb-2">Daftar Buku Tabungan</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($bukuTabungan as $bk)
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2"
                                        style="border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                                        <span style="font-size: 13px; font-weight: 500; color: #111827;">
                                            {{ $bk['bank']['nama'] }}
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
