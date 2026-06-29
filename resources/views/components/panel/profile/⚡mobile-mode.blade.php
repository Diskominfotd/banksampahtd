<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="ph-title">Profil & Setelan</div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="d-flex align-items-center gap-3 p-3 mb-4"
                style="background:#fff;border:1px solid var(--border);border-radius:16px">
                <div class="avatar" style="width:52px;height:52px;font-size:18px">
                    {{ strtoupper(Auth::user()->initials()) }}</div>
                <div class="flex-grow-1">
                    <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700">
                        {{ ucfirst(Auth::user()->name) }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ ucfirst(Auth::user()->roles->first()->name) }}
                    </div>
                    <div style="font-size:10px;color:var(--cyan);margin-top:2px">
                        {{ ucfirst(Auth::user()->unit->name) }}
                    </div>
                </div>
                <button wire:click="profileDetail" class="btn-tx"
                    @click="$store.sheet.show('edit-profile')">Edit</button>
                <button class="btn-tx" @click="$store.sheet.show('edit-password')">
                    Password</button>
            </div>
            @if (Auth::user()->hasRole(['supervisor']))
                <div class="d-flex align-items-center justify-content-between">
                    <div class="sec-lbl">Informasi Unit</div>
                    <button class="btn-tx"
                        @click="$store.sheet.show('edit-bank-sampah')">Edit</button>
                </div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="detail-field"><span class="df-key">Nama Bank Sampah</span><span
                            class="df-val">{{ $namaBank }}</span></div>
                    <div class="detail-field"><span class="df-key">Kode Unit</span><span
                            class="df-val">{{ $kodeBank }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Lokasi</span>
                        <span class="df-val">
                            Tanah Datar, Sumatera Barat
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Jam Operasional</span><span class="df-val">Sen–Jum,
                            {{ $jamBuka }}–{{ $jamTutup }}</span></div>
                    <div class="detail-field"><span class="df-key">Total Nasabah</span><span
                            class="df-val">{{ $this->nasabah }} nasabah
                            aktif</span></div>
                </div>
            @endif
        </div>
        @include('components.⚡mobile-bottombar')
    </div>

    <div x-show="$store.sheet.is('edit-profile')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('edit-profile')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Edit Profile
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="editProfile">
                <div wire:loading.flex wire:target="profileDetail" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div class="f-group"><label>Nama Lengkap</label>
                    <input class="f-input" type="text" wire:model="namaLengkap">
                    @error('namaLengkap')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>Nik</label>
                    <input class="f-input" type="tel" wire:model="nik">
                    @error('nik')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>Email</label>
                    <input class="f-input" type="email" wire:model="email">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>No Telepon</label>
                    <input class="f-input" type="text" wire:model="nomorTeleponNasabah">
                    @error('nomorTeleponNasabah')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button class="btn-primary mb-2" wire:click="editProfile" wire:loading.attr="disabled"
                    wire:target="editProfile">
                    <span wire:loading.remove wire:target="editProfile">Simpan</span>
                    <span wire:loading wire:target="editProfile">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <div x-show="$store.sheet.is('edit-password')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('edit-password')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Edit Password
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="editPassword">
                <div class="f-group">
                    <label>Password Baru</label>
                    <div class="position-relative" x-data="{ show: false }">
                        <input class="f-input" :type="show ? 'text' : 'password'" wire:model="password"
                            style="padding-right: 2.5rem;">
                        <button type="button" x-on:click="show = !show"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 0;">
                            <i x-show="!show" class="bi bi-eye-fill" style="font-size: 18px;"></i>
                            <i x-show="show" class="bi bi-eye-slash-fill" style="font-size: 18px;"></i>
                        </button>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button class="btn-primary mb-2" wire:click="editPassword" wire:loading.attr="disabled"
                    wire:target="editPassword">
                    <span wire:loading.remove wire:target="editPassword">Simpan</span>
                    <span wire:loading wire:target="editPassword">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <div x-show="$store.sheet.is('edit-bank-sampah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('edit-bank-sampah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Edit Unit Bank Sampah
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="editBankSampah">
                <div class="f-group"><label>Nama Bank Sampah</label>
                    <input class="f-input" type="text" wire:model="namaBank">
                    @error('namaBank')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group" x-data="{
                    kodeBank: @entangle('kodeBank'),
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
                        this.kodeBank = hasil;
                    }
                }">
                    <label>Kode Unit</label>
                    <div class="d-flex gap-2">
                        <input class="f-input" type="text" wire:model="kodeUnit" x-model="kodeBank"
                            maxlength="6" placeholder="ABC123">
                        <button type="button" class="btn btn-success" @click="generateKode()"
                            title="Generate Kode">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        @error('kodeUnit')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="f-group"><label>Kota / Kabupaten</label>
                    <input class="f-input" type="text" value="Tanah Datar" disabled>
                </div>
                <div class="f-group"><label>Provinsi</label>
                    <input class="f-input" type="text" value="Sumatera Barat" disabled>
                </div>
                <div class="f-group"><label>Alamat Lengkap</label>
                    <textarea class="f-input" wire:model="alamatBank" id="" cols="30" rows="2"></textarea>
                    @error('alamatBank')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>Nomor Telepon</label>
                    <input class="f-input" type="text" wire:model="nomorTelepon">
                    @error('nomorTelepon')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>Jam Buka</label>
                    <input class="f-input" type="time" wire:model="jamBuka">
                    @error('jamBuka')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="f-group"><label>Jam Tutup</label>
                    <input class="f-input" type="time" wire:model="jamTutup">
                    @error('jamTutup')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button class="btn-primary mb-2" wire:click="editBankSampah" wire:loading.attr="disabled"
                    wire:target="editBankSampah">
                    <span wire:loading.remove wire:target="editBankSampah">Simpan</span>
                    <span wire:loading wire:target="editBankSampah">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
