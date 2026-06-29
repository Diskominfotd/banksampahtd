<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Do what you can, with what you have, where you are. - Theodore Roosevelt --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Bank/Unit</div>
                        <div style="font-size:11px;color:var(--muted)">
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                        data-bs-target="#wm-tambah-unit"><i class="bi bi-plus-circle me-1"></i>Tambah
                        Unit</button>
                </div>
                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%">
                        <i class="bi bi-search si"></i>
                        <input wire:model.live="keyword" type="text" placeholder="Cari berdasarkan nama..."
                            style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['unit'] as $index => $unit)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="bs bs-ok">
                                            {{ strtoupper($unit->nama) ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="bs bs-warn">
                                            {{ $unit->telepon ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button wire:click="detail('{{ encrypt($unit->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-edit-unit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $data['unit']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="wm-tambah-unit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Bank/Unit Sampah Baru</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="createUnit">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-12" x-data="{
                                    kode: @entangle('kode'),
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
                                        this.kode = hasil;
                                    }
                                }" x-init="document.getElementById('wm-tambah-unit').addEventListener('show.bs.modal', () => {
                                    generateKode();
                                })">
                                    <label class="w-form-label">Kode Unit</label>
                                    <div class="d-flex gap-2">
                                        <input class="w-form-input" type="text" x-model="kode" maxlength="6"
                                            placeholder="ABC123" style="text-transform: uppercase;"
                                            @input="kode = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                            disabled>
                                        <button type="button" class="btn-tx" @click="generateKode()"
                                            title="Generate Kode">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    @error('kode')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="nama"
                                        placeholder="ex: Bank Sampah Sejahtera...">
                                    @error('nama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">No Telepon</label>
                                    <input class="w-form-input" type="text" wire:model="telepon"
                                        placeholder="ex: 08XXXXXXXX">
                                    @error('telepon')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="w-form-label">Alamat</label>
                                    <textarea class="w-form-input" wire:model="alamat" cols="30" rows="5"></textarea>
                                    @error('alamat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Jam Buka</label>
                                    <input class="w-form-input" type="time" wire:model="jamBuka">
                                    @error('jamBuka')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Jam Tutup</label>
                                    <input class="w-form-input" type="time" wire:model="jamTutup">
                                    @error('jamTutup')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="createUnit">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="createUnit">
                            <span wire:loading.remove wire:target="createUnit">Simpan</span>
                            <span wire:loading wire:target="createUnit">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-unit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Organisasi</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editUnit">
                    <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-12" x-data="{
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
                                    <label class="w-form-label">Kode Unit</label>
                                    <div class="d-flex gap-2">
                                        <input class="w-form-input" type="text" x-model="kodeUnit" maxlength="6"
                                            placeholder="ABC123" style="text-transform: uppercase;"
                                            @input="kodeUnit = $event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                            disabled>
                                        <button type="button" class="btn-tx" @click="generateKode()"
                                            title="Generate Kode">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    @error('kodeUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="namaUnit"
                                        placeholder="ex: Bank Sampah Sejahtera...">
                                    @error('namaUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">No Telepon</label>
                                    <input class="w-form-input" type="text" wire:model="teleponUnit"
                                        placeholder="ex: 08XXXXXXXX">
                                    @error('teleponUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="w-form-label">Alamat</label>
                                    <textarea class="w-form-input" wire:model="alamatUnit" cols="30" rows="5"></textarea>
                                    @error('alamatUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Jam Buka</label>
                                    <input class="w-form-input" type="time" wire:model="jamBukaUnit">
                                    @error('jamBukaUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Jam Tutup</label>
                                    <input class="w-form-input" type="time" wire:model="jamTutupUnit">
                                    @error('jamTutupUnit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="editCategory">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editCategory">
                            <span wire:loading.remove wire:target="editCategory">Simpan</span>
                            <span wire:loading wire:target="editCategory">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
