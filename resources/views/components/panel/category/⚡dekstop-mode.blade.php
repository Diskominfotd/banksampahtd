<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- It is never too late to be what you might have been. - George Eliot --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <!-- W-PAGE: Harga Sampah -->
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Kategori</div>
                        <div style="font-size:11px;color:var(--muted)">
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                        data-bs-target="#wm-tambah-kategori"><i class="bi bi-plus-circle me-1"></i>Tambah
                        Kategori</button>
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
                            @foreach ($data['categories'] as $index => $category)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="bs bs-ok">
                                            {{ strtoupper($category->name) ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button wire:click="detail('{{ encrypt($category->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-edit-kategori">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button"
                                            x-on:click="Swal.fire({
                                            title: 'Hapus Data Kategori ?',
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
                                            Livewire.dispatch('doDelete', { categoryId: '{{ encrypt($category->id) }}' })
                                            }
                                            })"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $data['categories']->links() }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-tambah-kategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Kategori</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="createCategory">
                    <div class="w-modal-body">
                        <label class="w-form-label">Nama Kategori</label>
                        <input class="w-form-input" type="text" wire:model="nama"
                            placeholder="Masukkan nama kategori">
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
    <div wire:ignore.self class="modal fade" id="wm-edit-kategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Kategori</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editCategory">
                    <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div class="w-modal-body">
                        <label class="w-form-label">Nama Kategori</label>
                        <input class="w-form-input" type="text" wire:model="namaKategori"
                            placeholder="Masukkan nama kategori">
                        @error('namaKategori')
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
