<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- No surplus words or unnecessary actions. - Marcus Aurelius --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Organisasi</div>
                        <div style="font-size:11px;color:var(--muted)">
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                        data-bs-target="#wm-tambah-org"><i class="bi bi-plus-circle me-1"></i>Tambah
                        Organisasi</button>
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
                                <th>Aksi</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($data['org'] as $index => $org)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="bs bs-ok">
                                            {{ strtoupper($org->nama) ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button wire:click="detail('{{ encrypt($org->id) }}')" class="w-btn w-btn-ghost"
                                            style="font-size:10px;padding:4px 10px" data-bs-toggle="modal"
                                            data-bs-target="#wm-edit-org">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $data['org']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-tambah-org" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Organisasi</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="createOrganisasi">
                    <div class="w-modal-body">
                        <label class="w-form-label">Nama</label>
                        <input class="w-form-input" type="text" wire:model="nama"
                            placeholder="Masukkan nama organisasi">
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="createCategory">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="createCategory">
                            <span wire:loading.remove wire:target="createCategory">Simpan</span>
                            <span wire:loading wire:target="createCategory">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-org" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Organisasi</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editOrganisasi">
                    <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div class="w-modal-body">
                        <label class="w-form-label">Nama</label>
                        <input class="w-form-input" type="text" wire:model="namaOrganisasi"
                            placeholder="Masukkan nama organisasi">
                        @error('namaOrganisasi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
