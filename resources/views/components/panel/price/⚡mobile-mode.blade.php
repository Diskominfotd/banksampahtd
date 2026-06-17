<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
    <div id="m-harga">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Harga Sampah</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-jenis')">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div wire:click="priceDetail" class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('update-harga')"><i class="bi bi-pencil-fill" style="font-size:12px"></i>
                </div>
            </div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama jenis sampah...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['trashs'] as $t)
                    <div class="list-item fade-up">
                        <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($t->trash->nama) }}</div>
                            <div class="list-sub">{{ ucfirst($t->trash->syarat) }}</div>
                            <div class="list-sub"><b>{{ ucfirst($t->trash->category->name) }}</b></div>
                            <div class="d-flex gap-1 mt-2">
                                <button @click="$store.sheet.show('update-jenis')"
                                    wire:click="detailJenis('{{ encrypt($t->trash->id) }}')" class="btn-tx">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button wire:click="alertDelete('{{ encrypt($t->id) }}')" class="btn-tx"><i
                                        class="bi bi-trash-fill"></i></button>
                            </div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--cyan)">
                                Rp {{ number_format($t->harga ?? 0, 0, ',', '.') }}</div>
                            <div style="font-size:9px;color:var(--muted)">/kg</div>
                            <div style="font-size:9px;color:var(--muted)"><b>Harga - {{ ucfirst($t->type) }}</b></div>
                        </div>

                    </div>
                @endforeach
            </div>
            @if ($data['trashs']->count() >= 10)
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
    </div>
    <div x-show="$store.sheet.is('update-harga')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('update-harga')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Update Harga Beli

        </div>
        <div
            style="display:flex;align-items:center;gap:8px;background:var(--bg-main,#f5f5f5);border:0.5px solid #e0e0e0;border-radius:12px;padding:0 12px;height:40px;margin-bottom:16px;">
            <i class="bi bi-search" style="font-size:14px;color:#aaa;flex-shrink:0;"></i>
            <input wire:model.live.debounce.300ms="searchPrice" type="text" placeholder="Cari jenis sampah..."
                style="flex:1;border:none;background:transparent;outline:none;font-size:13px;color:var(--text-main);" />
            <i class="bi bi-x" x-show="$wire.searchPrice" @click="$wire.set('searchPrice', '')"
                style="font-size:16px;color:#aaa;cursor:pointer;flex-shrink:0;" aria-hidden="true"></i>
        </div>
        @foreach ($prices as $index => $price)
            <div wire:loading.flex wire:target="priceDetail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            <div class="col-12">
                <label class="f-form-label">{{ $price['label'] }} (Rp/kg)</label>
                <div class="d-flex gap-2 mb-1 align-items-center" x-data="{
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
                }" x-init="init()">
                    <input class="f-input"type="text" x-model="formatted" @input="format($event.target.value)"
                        wire:ignore :disabled="$wire.prices[{{ $index }}]?.is_induk">
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
                        <input class="form-check-input" type="checkbox" id="harga_induk_{{ $index }}"
                            wire:model="prices.{{ $index }}.is_induk">

                        <label class="form-check-label text-muted small" for="harga_induk_{{ $index }}">
                            Gunakan harga induk
                        </label>
                    </div>
                @endif
            </div>
        @endforeach
        @if ($priceLimit)
            <button type="button" wire:click="loadMorePrices"
                style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                <span wire:loading.remove wire:target="loadMorePrices">Tampilkan lebih banyak</span>
                <span wire:loading wire:target="loadMorePrices">
                    <span class="spinner-border spinner-border-sm"
                        style="width:12px;height:12px;border-width:1.5px;"></span>
                </span>
            </button>
        @endif
        <div class="d-flex gap-2 mt-2">

        </div>
    </div>
    <div x-show="$store.sheet.is('tambah-jenis')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('tambah-jenis')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Tambah Jenis Sampah
        </div>
        <form wire:submit="newJenis">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="Nama Jenis Sampah">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Syarat</label>
                <input class="f-input" type="text" wire:model="syarat" placeholder="Syarat Jenis Sampah">
                @error('syarat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Kategori</label>
                <select class="f-input" wire:model="kategori">
                    <option hidden>Pilih Kategori</option>
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
            <div class="f-group" x-data="{
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
                <label>Harga/KG</label>
                <input class="f-input" type="text" placeholder="Rp 0" maxlength="20" x-model="formatted"
                    @input="format($event.target.value)" wire:ignore>
                @error('harga')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="newJenis">
                    <span wire:loading.remove wire:target="newJenis">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="newJenis">Loading...</span>
                </button>
            </div>
        </form>
    </div>
    <div x-show="$store.sheet.is('update-jenis')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('update-jenis')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Update Jenis Sampah
        </div>
        <div wire:loading.flex wire:target="detailJenis" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <form wire:submit="editJenis">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="namaJenis" placeholder="Nama Jenis Sampah">
                @error('namaJenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Syarat</label>
                <input class="f-input" type="text" wire:model="syaratJenis" placeholder="Syarat Jenis Sampah">
                @error('syaratJenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Kategori</label>
                <select class="f-input" wire:model="kategoriJenis">
                    <option hidden>Pilih Kategori</option>
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
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="editJenis">
                    <span wire:loading.remove wire:target="editJenis">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="newJenis">Loading...</span>
                </button>
            </div>
        </form>
    </div>
</div>
