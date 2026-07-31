<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')">
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
                <input wire:model.live="keyword" type="text" placeholder="Cari nama nasabah, rekening...">
            </div>
            <div class="d-flex flex-column gap-2">
                @forelse ($data['nasabah'] as $index => $nasabah)
                    <div class="tx-card d-flex align-items-start gap-2 fade-up">
                        <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)">
                            <div class="avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                                @if ($nasabah->avatar)
                                    <img src="{{ Storage::url($nasabah->avatar) }}" alt="{{ $nasabah->name }}"
                                        style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
                                @else
                                    {{ strtoupper($nasabah->initials()) }}
                                @endif
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="tx-name text-truncate">{{ ucfirst($nasabah->name) }}</div>
                            <div class="tx-date"><i class="bi bi-cash me-1"></i>
                                <strong>Saldo - Rp.
                                    {{ number_format($nasabah->bukuTabungans->sum('saldo'), 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex gap-1 mt-2">
                                <button @click="$store.sheet.show('edit-nasabah')"
                                    wire:click="detail('{{ encrypt($nasabah->id) }}')" class="btn-tx">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button  x-on:click="Swal.fire({
                                        title: 'Hapus Data Nasabah?',
                                        html: '<span style=\'color:#6b7280;font-size:14px\'>Data yang dihapus <b>tidak bisa dikembalikan</b>. Pastikan Anda yakin sebelum melanjutkan.</span>',
                                        icon: 'warning',
                                        iconColor: '#d33',
                                        showCancelButton: true,
                                        confirmButtonText: '<i class=\'bi bi-trash3 me-1\'></i> Ya, Hapus',
                                        cancelButtonText: 'Batal',
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#6b7280',
                                        reverseButtons: true,
                                        focusCancel: true,
                                        buttonsStyling: true,
                                        customClass: {
                                        popup: 'rounded-4 shadow-lg',
                                        title: 'fw-bold fs-5',
                                        confirmButton: 'px-4 py-2 rounded-3',
                                        cancelButton: 'px-4 py-2 rounded-3'
                                        },
                                        showClass: {
                                        popup: 'animate__animated animate__zoomIn animate__faster'
                                        },
                                        hideClass: {
                                        popup: 'animate__animated animate__zoomOut animate__faster'
                                        }
                                        }).then((result) => {
                                        if (result.isConfirmed) {
                                        Livewire.dispatch('doDelete', { userId: '{{ encrypt($nasabah->id) }}' })
                                        }
                                        })" class="btn-tx">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                <button @click="$store.sheet.show('rekening-nasabah')"
                                    wire:click="getBukuTabungan('{{ encrypt($nasabah->id) }}')" class="btn-tx">
                                    Rekening
                                </button>
                            </div>
                        </div>
                        <span class="bs bs-green flex-shrink-0">{{ ucfirst($nasabah->status) }}</span>
                    </div>
                @empty
                    <div class="item-empty">
                        <i class="bi bi-inbox"></i>
                        Tidak Ada Data
                    </div>
                @endforelse
            </div>
            @if ($data['nasabah']->count() >= 10)
                <button type="button" wire:click="loadPerpage"
                    style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                    <span wire:loading.remove wire:target="loadPerpage">Tampilkan lebih banyak</span>
                    <span wire:loading wire:target="loadPerpage">
                        <span class="spinner-border spinner-border-sm"
                            style="width:12px;height:12px;border-width:1.5px;"></span>
                    </span>
                </button>
            @endif
        </div>
        @include('components.⚡mobile-bottombar')
    </div>

    <div x-show="$store.sheet.is('tambah-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('tambah-nasabah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Daftarkan Nasabah Baru
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
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
                    <input class="f-input" type="text" wire:model="nik" placeholder="16 digit NIK KTP"
                        maxlength="16">
                    @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="f-group">
                    <label>No. HP</label>
                    <input class="f-input" type="tel" wire:model="nomorTelepon" placeholder="08xx-xxxx-xxxx">
                    @error('nomorTelepon')
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

                    <div x-data="{
                        search: '',
                        open: false,
                        selected: {{ Js::from($unitNasabah ?? '') }},
                        selectedLabel: {{ Js::from(collect($data['banksampah'])->firstWhere('id', $unitNasabah)['nama'] ?? 'Pilih Unit') }},
                        all: {{ Js::from($data['banksampah']) }},
                        locked: {{ Js::from($lock ?? false) }},
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
                    }" x-on:click.outside="open = false" class="position-relative">

                        {{-- Trigger --}}
                        <button type="button" x-on:click="!locked && (open = !open)" :disabled="locked"
                            class="f-input d-flex align-items-center justify-content-between w-100 text-start"
                            :class="locked ? 'locked-input' : ''" style="cursor: pointer;">
                            <span x-text="selectedLabel" :class="selected === '' ? 'text-muted' : ''"></span>
                            <i class="bi" :class="locked ? 'bi-lock-fill' : 'bi-chevron-down'"
                                style="font-size: 15px; transition: transform .2s; flex-shrink: 0;"
                                :style="open && !locked ? 'transform: rotate(180deg)' : ''"></i>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            style="position: absolute; bottom: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
                            background: #fff; border: 1.5px solid #dee2e6;
                            border-radius: 10px; overflow: hidden;
                            box-shadow: 0 -4px 24px rgba(0,0,0,.08), 0 -1px 6px rgba(0,0,0,.04);">

                            {{-- Search --}}
                            <div style="padding: 10px 10px 6px;">
                                <div class="position-relative">
                                    <i class="bi bi-search position-absolute text-muted"
                                        style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                                    <input type="text" x-model="search" x-on:click.stop x-init="$watch('open', v => v && $nextTick(() => $el.focus()))"
                                        placeholder="Cari unit..." class="f-input"
                                        style="padding-left: 34px; font-size: 13px; height: 36px;">
                                </div>
                            </div>

                            <div style="height: 1px; background: #f0f0f0; margin: 0 10px;"></div>

                            {{-- Options --}}
                            <div style="max-height: 180px; overflow-y: auto; padding: 6px;">
                                <template x-if="filtered.length === 0">
                                    <div class="text-center text-muted py-3" style="font-size: 13px;">
                                        <i class="ti ti-mood-empty d-block mb-1" style="font-size: 22px;"></i>
                                        Tidak ditemukan
                                    </div>
                                </template>
                                <template x-for="option in filtered" :key="option.id">
                                    <div x-on:click="select(option.id, option.nama)"
                                        style="padding: 8px 10px; font-size: 13px; border-radius: 6px;
                               cursor: pointer; transition: background .15s;
                               display: flex; align-items: center; justify-content: space-between;"
                                        :style="selected == option.id ?
                                            'background: #e7f0ff; color: #0d6efd; font-weight: 500;' :
                                            'color: #212529;'"
                                        x-on:mouseenter="if (selected != option.id) $el.style.background = '#f8f9fa'"
                                        x-on:mouseleave="if (selected != option.id) $el.style.background = 'transparent'">
                                        <span x-text="option.nama"></span>
                                        <i class="ti ti-check" style="font-size: 14px;"
                                            x-show="selected == option.id"></i>
                                    </div>
                                </template>
                            </div>

                            {{-- Footer --}}
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
                <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn-outline w-100" style="width:100%"
                        @click="$store.sheet.hide()">Batal</button>
                    <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                        wire:target="registerNasabah">
                        <span wire:loading.remove wire:target="registerNasabah">
                            <i class="bi bi-person-plus me-1"></i>Simpan
                        </span>
                        <span wire:loading wire:target="registerNasabah">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Backdrop edit --}}
    <div x-show="$store.sheet.is('edit-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>

    {{-- Sheet edit --}}
    <div x-show="$store.sheet.is('edit-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('edit-nasabah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Edit Nasabah
            </div>
        </div>
        <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="editNasabah">
                <div class="f-group">
                    <label>Nama Lengkap / Nama Usaha</label>
                    <input class="f-input" type="text" wire:model="namaNasabah">
                    @error('namaNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group">
                    <label>Nik</label>
                    <input class="f-input" type="text" wire:model="nikNasabah">
                    @error('nikNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group">
                    <label>No. HP</label>
                    <input class="f-input" type="tel" wire:model="nomorTeleponNasabah" placeholder="08xx-xxxx-xxxx">
                    @error('nomorTeleponNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group">
                    <label>Email</label>
                    <input class="f-input" type="email" wire:model="emailNasabah">
                    @error('emailNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group">
                    <label>Jenis Nasabah</label>
                    <select class="f-input" wire:model.live="jenisNasabah">
                        <option value="perorangan">Perorangan</option>
                        <option value="kelompok">Kelompok</option>
                    </select>
                </div>
                <div class="f-group">
                    <label>Organisasi</label>
                    <select class="f-input" wire:model="organisasiNasabah" @disabled($jenisNasabah === 'perorangan')>
                        <option value="">Pilih Organisasi</option>
                        @foreach ($data['organisasi'] as $org)
                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                        @endforeach
                    </select>
                    @error('organisasiNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group">
                    <label>Unit - {{ $namaUnitNasabah ?? '-' }}</label>

                    <div x-data="{
                        search: '',
                        open: false,
                        selected: $wire.entangle('unitNasabah'),
                        selectedLabel: 'Pilih Unit',
                        all: {{ Js::from($data['banksampah']) }},
                        locked: {{ Js::from($lock ?? false) }},
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
                    }" x-on:click.outside="open = false" class="position-relative">

                        {{-- Trigger --}}
                        <button type="button" x-on:click="!locked && (open = !open)" :disabled="locked"
                            class="f-input d-flex align-items-center justify-content-between w-100 text-start"
                            :class="locked ? 'locked-input' : ''" style="cursor: pointer;">
                            <span x-text="selectedLabel" :class="selected === '' ? 'text-muted' : ''"></span>
                            <i class="bi" :class="locked ? 'bi-lock-fill' : 'bi-chevron-down'"
                                style="font-size: 15px; transition: transform .2s; flex-shrink: 0;"
                                :style="open && !locked ? 'transform: rotate(180deg)' : ''"></i>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            style="position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1050;
                            background: #fff; border: 1.5px solid #dee2e6;
                            border-radius: 10px; overflow: hidden;
                            box-shadow: 0 4px 24px rgba(0,0,0,.08), 0 1px 6px rgba(0,0,0,.04);">

                            {{-- Search --}}
                            <div style="padding: 10px 10px 6px;">
                                <div class="position-relative">
                                    <i class="ti ti-search position-absolute text-muted"
                                        style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                                    <input type="text" x-model="search" x-on:click.stop x-init="$watch('open', v => v && $nextTick(() => $el.focus()))"
                                        placeholder="Cari unit..." class="f-input"
                                        style="padding-left: 34px; font-size: 13px; height: 36px;">
                                </div>
                            </div>

                            <div style="height: 1px; background: #f0f0f0; margin: 0 10px;"></div>

                            {{-- Options --}}
                            <div style="max-height: 180px; overflow-y: auto; padding: 6px;">
                                <template x-if="filtered.length === 0">
                                    <div class="text-center text-muted py-3" style="font-size: 13px;">
                                        <i class="ti ti-mood-empty d-block mb-1" style="font-size: 22px;"></i>
                                        Tidak ditemukan
                                    </div>
                                </template>
                                <template x-for="option in filtered" :key="option.id">
                                    <div x-on:click="select(option.id, option.nama)"
                                        style="padding: 8px 10px; font-size: 13px; border-radius: 6px;
                               cursor: pointer; transition: background .15s;
                               display: flex; align-items: center; justify-content: space-between;"
                                        :style="selected == option.id ?
                                            'background: #e7f0ff; color: #0d6efd; font-weight: 500;' :
                                            'color: #212529;'"
                                        x-on:mouseenter="if (selected != option.id) $el.style.background = '#f8f9fa'"
                                        x-on:mouseleave="if (selected != option.id) $el.style.background = 'transparent'">
                                        <span x-text="option.nama"></span>
                                        <i class="ti ti-check" style="font-size: 14px;"
                                            x-show="selected == option.id"></i>
                                    </div>
                                </template>
                            </div>

                            {{-- Footer --}}
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
                <div class="f-group">
                    <label class="w-form-label">Jadikan Admin</label>
                    <div class="d-flex align-items-center" style="height: 42px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" wire:model.live="isAdmin"
                                id="checkIsAdmin" style="cursor:pointer;">
                            <label class="form-check-label" for="checkIsAdmin" style="font-size:13px;">
                                <span x-text="$wire.isAdmin ? 'Ya' : 'Tidak'"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                        wire:target="editNasabah">
                        <span wire:loading.remove wire:target="editNasabah">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </span>
                        <span wire:loading wire:target="editNasabah">Loading...</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- Backdrop Rekening --}}
    <div x-show="$store.sheet.is('rekening-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    {{-- Sheet Rekening --}}
    <div x-show="$store.sheet.is('rekening-nasabah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;
       background:var(--bg-card,#fff);border-radius:20px 20px 0 0;
       padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;
            margin-bottom:18px;color:var(--text-main)">
            Rekening Nasabah
        </div>
        <div wire:loading.flex wire:target="getBukuTabungan" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>

        <form wire:submit="addBukuTabungan">
            <div class="f-group">
                <label>Unit</label>
                <select class="f-input" wire:model="unitBukuTabungan">
                    <option value="">Pilih Unit</option>
                    @foreach ($data['banksampah'] as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                    @endforeach
                </select>
                @error('unitBukuTabungan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                    wire:target="addBukuTabungan">
                    <span wire:loading.remove wire:target="addBukuTabungan">
                        <i class="bi bi-check-lg me-1"></i>Tambahkan
                    </span>
                    <span wire:loading wire:target="addBukuTabungan">Loading...</span>
                </button>
            </div>
        </form>
        <div style="margin-top:20px">
            <div style="font-size:13px;font-weight:600;color:var(--text-main);margin-bottom:10px">
                Daftar Buku Tabungan
            </div>
            @if (!empty($bukuTabungan))
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach ($bukuTabungan as $bk)
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;border-radius:12px;
                                background:var(--bg-body,#f9fafb);
                                border:1px solid var(--border-color,#e5e7eb)">
                            <div style="display:flex;align-items:center;gap:10px">
                                <div
                                    style="width:34px;height:34px;border-radius:50%;
                                        background:#dcfce7;display:flex;align-items:center;
                                        justify-content:center">
                                    <i class="bi bi-bank" style="font-size:14px;color:#16a34a"></i>
                                </div>
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:var(--text-main)">
                                        {{ $bk['bank']['nama'] }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted,#6b7280);font-family:monospace">
                                        {{ $bk['nomor_rekening'] }}
                                    </div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right" style="font-size:13px;color:var(--text-muted,#9ca3af)"></i>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                    <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                    Belum ada buku tabungan
                </div>
            @endif
        </div>

    </div>
</div>
