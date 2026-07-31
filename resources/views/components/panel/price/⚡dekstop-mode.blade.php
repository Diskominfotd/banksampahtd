<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Be present above all else. - Naval Ravikant --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Harga Beli
                            Sampah</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        @if (Auth::user()->hasRole(['supervisor']))
                            <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                                data-bs-target="#wm-tambah-jenis">
                                <i class="bi bi-patch-plus me-1"></i>Tambah Jenis
                            </button>
                        @endif

                        <button wire:click="priceDetail" class="w-btn w-btn-primary" style="font-size:11px"
                            data-bs-toggle="modal" data-bs-target="#wm-update-harga">
                            <i class="bi bi-pencil-fill me-1"></i>Update Harga
                        </button>
                    </div>
                </div>
                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%">
                        <i class="bi bi-search si"></i>
                        <input wire:model.live="keyword" type="text" placeholder="Cari jenis sampah..."
                            style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis Sampah</th>
                                <th>Syarat</th>
                                <th>Harga/kg</th>
                                <th>Type Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['trashs'] as $t)
                                <tr>
                                    <td><span class="bs bs-ok">{{ ucfirst($t->trash->category->name) }}</span></td>
                                    <td style="font-size:11px;font-weight:600">{{ ucfirst($t->trash->nama) }}</td>
                                    <td style="font-size:10px;color:var(--muted)">{{ ucfirst($t->syarat) }}</td>
                                    <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">
                                        Rp {{ number_format($t->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ ucfirst($t->type) }}
                                    </td>
                                    <td>
                                        @if (Auth::user()->hasRole(['supervisor']))
                                            <button type="button"
                                            x-on:click="Swal.fire({
                                            title: 'Hapus Data Harga?',
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
                                            Livewire.dispatch('doDelete', { trashId: '{{ encrypt($t->trash->id) }}' })
                                            }
                                            })"
                                                class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                            <button wire:click="detailJenis('{{ encrypt($t->trash->id) }}')"
                                                class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                                data-bs-toggle="modal" data-bs-target="#wm-edit-jenis">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $data['trashs']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Jenis Sampah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editJenis">
                    <div class="w-modal-body">
                        <div wire:loading.flex wire:target="detailJenis"
                            class="justify-content-center align-items-center"
                            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                            <div class="spinner-border text-success"></div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Kategori</label>
                                    <select class="w-form-input" wire:model.live="kategoriJenis">
                                        <option value="" hidden>Pilih Kategori</option>
                                        @foreach ($data['categories'] as $category)
                                            <option value="{{ $category->id }}">
                                                {{ ucfirst($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategoriJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="namaJenis"
                                        placeholder="Nama Jenis Sampah" maxlength="100">
                                    @error('namaJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    <label class="w-form-label">Syarat</label>
                                    <input class="w-form-input" type="text" wire:model="syaratJenis"
                                        placeholder="Syarat Jenis Sampah" maxlength="100">
                                    @error('syaratJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="editJenis">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editJenis">
                            <span wire:loading.remove wire:target="editJenis">Simpan</span>
                            <span wire:loading wire:target="editJenis">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-tambah-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Daftarkan Jenis Sampah Baru</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="newJenis">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Kategori</label>
                                    <select class="w-form-input" wire:model.live="kategori">
                                        <option value="" hidden>Pilih Kategori</option>
                                        @foreach ($data['categories'] as $category)
                                            <option value="{{ $category->id }}">
                                                {{ ucfirst($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="nama"
                                        placeholder="Nama Jenis Sampah" maxlength="100">
                                    @error('nama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    <label class="w-form-label">Syarat</label>
                                    <input class="w-form-input" type="text" wire:model="syarat"
                                        placeholder="Syarat Jenis Sampah" maxlength="100">
                                    @error('syarat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div x-data="{
                                    raw: '',
                                    formatted: '',
                                    format(val) {
                                        let num = val.replace(/\D/g, '');
                                        if (!num) {
                                            this.formatted = '';
                                            this.raw = '';
                                            $wire.set('harga', null); // reset ke null kalau kosong
                                            return;
                                        }
                                        let number = parseInt(num, 10);
                                        this.raw = number;
                                        this.formatted = 'Rp ' + number.toLocaleString('id-ID');
                                        $wire.set('harga', number); // sync langsung ke Livewire property
                                    }
                                }">
                                    <label class="w-form-label">Harga/Kg</label>
                                    <input class="w-form-input" type="text" placeholder="Rp 0" maxlength="20"
                                        x-model="formatted" @input="format($event.target.value)" wire:ignore>
                                    @error('harga')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="newJenis">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="newJenis">
                            <span wire:loading.remove wire:target="newJenis">Daftarkan</span>
                            <span wire:loading wire:target="newJenis">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-update-harga" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Harga</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="m-search mb-3 ms-2 me-2">
                    <i class="bi bi-search si"></i>
                    <input type="text" wire:model.live="searchPrice" placeholder="Cari nama jenis sampah...">
                </div>
                <div class="w-modal-body" style="overflow-y: auto; max-height: 60vh;">
                    <div wire:loading.flex wire:target="priceDetail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div class="row g-3">
                        @foreach ($prices as $index => $price)
                            <div class="col-12">
                                <label class="w-form-label">{{ $price['label'] }} (Rp/kg)</label>
                                <div class="d-flex gap-2 align-items-center" x-data="{
                                    formatted: '',
                                    init() {
                                        this.syncFromWire();
                                        $watch(() => $wire.prices[{{ $index }}]?.value, () => {
                                            this.syncFromWire();
                                        });
                                    },
                                    syncFromWire() {
                                        let val = $wire.prices[{{ $index }}]?.value;
                                        if (!val) {
                                            this.formatted = '';
                                            return;
                                        }
                                        this.formatted = 'Rp ' + Number(val).toLocaleString('id-ID');
                                    },
                                    format(val) {
                                        let num = val.replace(/\D/g, '');
                                        if (!num) {
                                            this.formatted = '';
                                            $wire.set('prices.{{ $index }}.value', null);
                                            return;
                                        }
                                        let number = parseInt(num, 10);
                                        this.formatted = 'Rp ' + number.toLocaleString('id-ID');
                                        $wire.set('prices.{{ $index }}.value', number);
                                    }
                                }"
                                    x-init="init()">
                                    <input class="w-form-input flex-grow-1" type="text" x-model="formatted"
                                        @input="format($event.target.value)" wire:ignore
                                        :disabled="$wire.prices[{{ $index }}]?.is_induk">

                                    <button type="button" title="Ubah"
                                        style="width:28px;height:28px;flex-shrink:0;border-radius:50%;background:none;border:0.5px solid #198754;color:#198754;display:flex;align-items:center;justify-content:center;padding:0;"
                                        wire:click="updatePrice({{ $index }})" wire:loading.attr="disabled"
                                        wire:target="updatePrice({{ $index }})">
                                        <span wire:loading.remove wire:target="updatePrice({{ $index }})">
                                            <i class="bi bi-check2" style="font-size:14px;"></i>
                                        </span>
                                        <span wire:loading wire:target="updatePrice({{ $index }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                style="width:12px;height:12px;border-width:1.5px;"></span>
                                        </span>
                                    </button>
                                </div>
                                @if (Auth::user()->unit->parent_id)
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox"
                                            id="harga_induk_{{ $index }}"
                                            wire:model="prices.{{ $index }}.is_induk">
                                        <label class="form-check-label text-muted small"
                                            for="harga_induk_{{ $index }}">
                                            Gunakan harga induk
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" wire:click="loadMorePrices"
                        style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                        <span wire:loading.remove wire:target="loadMorePrices">Tampilkan lebih banyak</span>
                        <span wire:loading wire:target="loadMorePrices">
                            <span class="spinner-border spinner-border-sm"
                                style="width:12px;height:12px;border-width:1.5px;"></span>
                        </span>
                    </button>
                </div>
                <div class="w-modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>
