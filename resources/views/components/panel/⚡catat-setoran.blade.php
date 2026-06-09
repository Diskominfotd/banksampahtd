<?php

use Livewire\Component;

new class extends Component {
    public $selectedNasabah = null;
    public $items = [];
};
?>

<div>
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Pencatatan Setoran</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search">
                        <i class="bi bi-search si"></i>
                        <input type="text" placeholder="Cari nasabah, setoran...">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end">
                            <div class="w-uname">Budi Santoso</div>
                            <div class="w-urole">Pengelola Bank Sampah</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>

            <div id="w-setoran" class="w-content">
                <div style="display:grid;grid-template-columns:300px 1fr;gap:16px;align-items:start">

                    {{-- Kiri: Nasabah --}}
                    <div class="w-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div style="font-size:12px;font-weight:700">Nasabah</div>
                                <div style="font-size:10px;color:var(--muted)">Pilih nasabah penyetor</div>
                            </div>
                            <button data-bs-toggle="modal" data-bs-target="#wm-pilih-nasabah"
                                class="w-btn w-btn-primary" style="font-size:11px;width:auto;padding:6px 12px">
                                <i class="bi bi-person-check me-1"></i> Pilih
                            </button>
                        </div>
                        <div
                            style="padding:24px 16px;text-align:center;background:var(--bg-dark);border-radius:10px;border:1.5px dashed var(--border)">
                            <i class="bi bi-person-dash" style="font-size:22px;color:var(--muted)"></i>
                            <div style="font-size:11px;color:var(--muted);margin-top:6px">Belum ada nasabah dipilih
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Item + Aksi --}}
                    <div class="d-flex flex-column gap-3">
                        <div class="w-panel">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div style="font-size:12px;font-weight:700">Item Setoran</div>
                                    <div style="font-size:10px;color:var(--muted)">Tambahkan jenis sampah yang disetor
                                    </div>
                                </div>
                                <button data-bs-toggle="modal" data-bs-target="#wm-pilih-jenis"
                                    class="w-btn w-btn-primary" class="w-btn w-btn-primary"
                                    style="font-size:11px;width:auto;padding:6px 12px">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <table class="w-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Sampah</th>
                                        <th>Berat</th>
                                        <th>Harga/kg</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6"
                                            style="text-align:center;padding:28px 10px;color:var(--muted);font-size:11px">
                                            <i class="bi bi-inbox"
                                                style="font-size:20px;display:block;margin-bottom:6px"></i>
                                            Belum ada item ditambahkan
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button class="w-btn w-btn-ghost">Batal</button>
                            <button class="w-btn w-btn-primary" style="width:auto;padding:7px 16px">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Setoran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-pilih-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Pilih Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>

            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-pilih-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Pilih Jenis Sampah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
