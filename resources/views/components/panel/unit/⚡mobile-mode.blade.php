<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- An unexamined life is not worth living. - Socrates --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')">
                <i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Data Bank/Unit</div>
            <div class="ms-auto">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-unit')">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
        </div>

        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama unit...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['unit'] as $index => $unit)
                    <div class="list-item fade-up">
                        <div class="list-ico ic1">
                            <i class="bi bi-bank" style="font-size:12px"></i>
                        </div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($unit->nama) }}</div>
                        </div>
                        <button wire:click="detail('{{ encrypt($unit->id) }}')" class="btn-tx"
                            @click="$store.sheet.show('edit-unit')"> <i class="bi bi-pencil-square me-1"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            @if ($data['unit']->count() >= 10)
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
    <div x-show="$store.sheet.is('tambah-unit')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('tambah-unit')" x-transition:enter="transition ease-out duration-300"
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
        <form wire:submit="createUnit">
            <div class="f-group" x-data="{
                kode: @entangle('kode'),
                generateKode() {
                    const huruf = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    const angka = '0123456789';
                    let hasil = '';
                    for (let i = 0; i < 3; i++) {
                        hasil += huruf.charAt(Math.floor(Math.random() * huruf.length));
                    }
                    hasil += '-';
                    for (let i = 0; i < 3; i++) {
                        hasil += angka.charAt(Math.floor(Math.random() * angka.length));
                    }
                    this.kode = hasil;
                }
            }" x-init="document.getElementById('wm-tambah-unit').addEventListener('show.bs.modal', () => {
                generateKode();
            })">
                <label>Kode Unit</label>
                <div class="d-flex gap-2">
                    <input class="f-input" type="text" wire:model="kode" x-model="kode" maxlength="6"
                         placeholder="ABC-123" style="text-transform: uppercase;"
                                            @input="kode = $event.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '')"
                                            disabled>
                    <button type="button" class="btn btn-success" @click="generateKode()" title="Generate Kode">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    @error('kode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="ex : Bank Unit Satu">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Nomo Telepon</label>
                <input class="f-input" type="text" wire:model="telepon" placeholder="ex : 08xxxxxxxxx">
                @error('telepon')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Jam Buka</label>
                <input class="f-input" type="time" wire:model="jamBuka">
                @error('jamBuka')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Jam Tutup</label>
                <input class="f-input" type="time" wire:model="jamTutup">
                @error('jamTutup')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group"><label>Alamat Lengkap</label>
                <textarea class="f-input" wire:model="alamat" id="" cols="30" rows="2"></textarea>
                @error('alamat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="createCategory">
                    <span wire:loading.remove wire:target="createCategory">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="createCategory">Loading...</span>
                </button>
            </div>
        </form>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — backdrop ======= --}}
    <div x-show="$store.sheet.is('edit-unit')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('edit-unit')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>

        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Edit Bank/Unit
        </div>
        <form wire:submit="editUnit">
            <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            {{-- <div class="f-group" x-data="{
                kodeUnit: @entangle('kodeUnit'),
                generateKode() {
                    const huruf = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    const angka = '0123456789';
                    let hasil = '';
                    for (let i = 0; i < 3; i++) {
                        hasil += huruf.charAt(Math.floor(Math.random() * huruf.length));
                    }
                    for (let i = 0; i < 3; i++) {
                        hasil += angka.charAt(Math.floor(Math.random() * angka.length));
                    }
                    this.kodeUnit = hasil;
                }
            }">
                <label>Kode Unit</label>
                <div class="d-flex gap-2">
                    <input class="f-input" type="text" wire:model="kodeUnit" x-model="kodeUnit" maxlength="6"
                        placeholder="ABC123">
                    <button type="button" class="btn btn-success" @click="generateKode()" title="Generate Kode">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    @error('kodeUnit')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div> --}}
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="namaUnit" placeholder="ex : Bank Unit Satu">
                @error('namaUnit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Nomo Telepon</label>
                <input class="f-input" type="text" wire:model="teleponUnit" placeholder="ex : 08xxxxxxxxx">
                @error('teleponUnit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Jam Buka</label>
                <input class="f-input" type="time" wire:model="jamBukaUnit">
                @error('jamBukaUnit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Jam Tutup</label>
                <input class="f-input" type="time" wire:model="jamTutupUnit">
                @error('jamTutupUnit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group"><label>Alamat Lengkap</label>
                <textarea class="f-input" wire:model="alamatUnit"cols="30" rows="2"></textarea>
                @error('alamatUnit')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="editUnit">
                    <span wire:loading.remove wire:target="editUnit">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="editUnit">Loading...</span>
                </button>
            </div>
        </form>
    </div>
</div>
