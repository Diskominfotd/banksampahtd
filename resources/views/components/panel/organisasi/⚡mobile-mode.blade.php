<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')">
                <i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Data Organisasi</div>
            <div class="ms-auto">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-org')">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
        </div>

        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama organisasi...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['org'] as $index => $org)
                    <div class="list-item fade-up">
                        <div class="list-ico ic1">
                            <i class="bi bi-grid-fill" style="font-size:12px"></i>
                        </div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($org->nama) }}</div>
                        </div>
                        <button wire:click="detail('{{ encrypt($org->id) }}')" class="btn-tx"
                            @click="$store.sheet.show('edit-org')"> <i class="bi bi-pencil-square me-1"></i></button>
                        <button wire:click="alertDelete('{{ encrypt($org->id) }}')" class="btn-tx"> <i
                                class="bi bi-trash me-1"></i></button>
                    </div>
                @endforeach
            </div>
            @if ($data['org']->count() >= 10)
                <button type="button" wire:click="loadMore"
                    style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                    <span wire:loading.remove wire:target="loadMore">Tampilkan lebih banyak</span>
                    <span wire:loading wire:target="loadMore">
                        <span class="spinner-border spinner-border-sm"
                            style="width:12px;height:12px;border-width:1.5px;"></span>
                    </span>
                </button>
            @endif
        </div>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — backdrop ======= --}}
    <div x-show="$store.sheet.is('tambah-org')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('tambah-org')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>

        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Kategori Baru
        </div>
        <form wire:submit="createOrganisasi">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="Organisasi...">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="createOrganisasi">
                    <span wire:loading.remove wire:target="createOrganisasi">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="createOrganisasi">Loading...</span>
                </button>
            </div>
        </form>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — backdrop ======= --}}
    <div x-show="$store.sheet.is('edit-org')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('edit-org')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>

        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Edit Organisasi
        </div>
        <form wire:submit="editOrganisasi">
            <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="namaOrganisasi" placeholder="Organisasi...">
                @error('namaOrganisasi')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="editOrganisasi">
                    <span wire:loading.remove wire:target="editOrganisasi">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="editOrganisasi">Loading...</span>
                </button>
            </div>
        </form>
    </div>
</div>
