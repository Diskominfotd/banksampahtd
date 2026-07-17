<?php

use Livewire\Component;
new class extends Component {};
?>

<div>
    {{-- Order your soul. Reduce your wants. - Augustine --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-setelan" class="w-content">
                <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;margin-bottom:16px">Profil &
                    Setelan</div>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="prof-card">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="avatar" style="width:56px;height:56px;font-size:20px">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ Storage::url(Auth::user()->avatar) }}"
                                            alt="{{ Auth::user()->name }}"
                                            style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
                                    @else
                                        {{ strtoupper(Auth::user()->initials()) }}
                                    @endif
                                </div>
                                <div>
                                    <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700">
                                        {{ ucfirst(Auth::user()->name) }}
                                    </div>
                                    <div style="font-size:11px;color:var(--muted)">
                                        {{ ucfirst(Auth::user()->roles->first()->name) }}
                                    </div>
                                    <button wire:click="profileDetail" class="w-btn w-btn-ghost"
                                        style="font-size:10px;padding:4px 12px;margin-top:6px" data-bs-toggle="modal"
                                        data-bs-target="#wm-edit-profile">
                                        Edit Profile
                                    </button>
                                    <button class="w-btn w-btn-ghost"
                                        style="font-size:10px;padding:4px 12px;margin-top:6px" data-bs-toggle="modal"
                                        data-bs-target="#wm-edit-password">
                                        Ubah Password
                                    </button>
                                </div>
                            </div>
                            <div class="prof-field"><label>Nik</label>
                                <span>{{ Auth::user()->nik }}</span>
                            </div>
                            <div class="prof-field"><label>Email</label>
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                            <div class="prof-field"><label>No. HP</label>
                                <span>{{ Auth::user()->nomor_hp }}</span>
                            </div>
                            <div class="prof-field"><label>Kode Unit Pendaftaran</label>
                                <span>{{ Auth::user()->unit->kode_bank }}</span>
                            </div>
                            <div class="prof-field"><label>Bergabung</label>
                                <span>{{ Auth::user()->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->hasRole(['supervisor']))
                        <div class="col-8">
                            <div class="w-panel">
                                <div class="w-panel-title">Informasi Bank Sampah</div>
                                <form wire:submit="editBankSampah">
                                    <div class="row g-3">
                                        <div class="col-6"><label class="w-form-label">Nama Bank Sampah</label><input
                                                class="w-form-input" type="text" wire:model="namaBank">
                                            @error('namaBank')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6" x-data="{
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
                                            <label class="w-form-label">Kode Unit</label>
                                            <div class="d-flex gap-2">
                                                <input class="w-form-input" type="text" x-model="kodeBank"
                                                    maxlength="6" placeholder="ABC123"
                                                    style="text-transform: uppercase;"
                                                    @input="kodeBank = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                                    disabled>
                                                <button type="button" class="btn-tx" @click="generateKode()"
                                                    title="Generate Kode">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </div>
                                            @error('kodeBank')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6"><label class="w-form-label">Kota / Kabupaten</label><input
                                                class="w-form-input" type="text" value="Tanah Datar" disabled>
                                        </div>
                                        <div class="col-6"><label class="w-form-label">Provinsi</label><input
                                                class="w-form-input" type="text" value="Sumatera Barat" disabled>
                                        </div>
                                        <div class="col-12"><label class="w-form-label">Alamat Lengkap</label>
                                            <textarea class="w-form-input" rows="2" wire:model="alamatBank"></textarea>
                                            @error('alamatBank')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6"><label class="w-form-label">Nomor Telepon</label><input
                                                class="w-form-input" type="text" wire:model="nomorTelepon">
                                            @error('nomorTelepon')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6"><label class="w-form-label">Jam Buka</label><input
                                                class="w-form-input" type="time" wire:model="jamBuka">
                                            @error('jamBuka')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-6"><label class="w-form-label">Jam Tutup</label><input
                                                class="w-form-input" type="time" wire:model="jamTutup">
                                            @error('jamTutup')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="submit" class="w-btn w-btn-primary"
                                                    wire:loading.attr="disabled" wire:target="editBankSampah">
                                                    <span wire:loading.remove wire:target="editBankSampah">Simpan</span>
                                                    <span wire:loading wire:target="editBankSampah">
                                                        <span class="spinner-border spinner-border-sm me-1"
                                                            role="status"></span>
                                                        Loading...
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-profile" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Edit Profile</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editProfile">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">

                            {{-- FOTO PROFILE --}}
                            <div class="d-flex flex-column align-items-center gap-2">
                                <div class="position-relative">
                                    @if ($fotoProfile)
                                        <img src="{{ $fotoProfile->temporaryUrl() }}" class="rounded-circle"
                                            style="width:100px;height:100px;object-fit:cover;">
                                    @elseif ($fotoProfileLama)
                                        <img src="{{ Storage::url($fotoProfileLama) }}" class="rounded-circle"
                                            style="width:100px;height:100px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                            style="width:100px;height:100px;">
                                            <i class="bi bi-person-fill text-white fs-2"></i>
                                        </div>
                                    @endif

                                    <label for="fotoProfileInput"
                                        class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:32px;height:32px;cursor:pointer;">
                                        <i class="bi bi-camera-fill"></i>
                                    </label>
                                    <input type="file" id="fotoProfileInput" class="d-none"
                                        wire:model="fotoProfile" accept="image/*">
                                </div>

                                <div wire:loading wire:target="fotoProfile" class="text-muted small">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Mengunggah...
                                </div>

                                @error('fotoProfile')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- END FOTO PROFILE --}}

                            <div>
                                <label class="w-form-label">Nama Lengkap</label>
                                <input class="w-form-input" type="text" wire:model="namaLengkap">
                                @error('namaLengkap')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <label class="w-form-label">Nik</label>
                                <input class="w-form-input" type="text" wire:model="nik">
                                @error('nik')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <label class="w-form-label">Email</label>
                                <input class="w-form-input" type="email" wire:model="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div>
                                <label class="w-form-label">Nomor Telepon</label>
                                <input class="w-form-input" type="text" wire:model="nomorTeleponNasabah">
                                @error('nomorTeleponNasabah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editProfile">
                            <span wire:loading.remove wire:target="editProfile">Simpan</span>
                            <span wire:loading wire:target="editProfile">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Loading...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-password" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Edit Password</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editPassword">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div x-data="{ show: false }">
                                <label class="w-form-label">Password Baru</label>
                                <div class="position-relative">
                                    <input class="w-form-input" :type="show ? 'text' : 'password'"
                                        wire:model="password" style="padding-right: 2.5rem;">
                                    <button type="button" @click="show = !show"
                                        style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: #6c757d;">
                                        <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editPassword">
                            <span wire:loading.remove wire:target="editPassword">Simpan</span>
                            <span wire:loading wire:target="editPassword">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Loading...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
