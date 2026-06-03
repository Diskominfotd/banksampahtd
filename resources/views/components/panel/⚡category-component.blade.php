<?php

use Livewire\Component;
use App\Services\UserServices;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    protected UserServices $userService;
    public ?string $nama = '';

    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }

    public function createCategory()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:categories,name',
        ]);
        $this->userService->createCategory([
            'name' => $this->nama,
        ]);
        $this->reset('nama');
        $this->dispatch('close-modal');
    }

    public function getData()
    {
        $categoriyBuilder = $this->userService->categoriesBuilder();
        $categories = $categoriyBuilder->latest()->paginate(10);
        return [
            'categories' => $categories,
        ];
    }
};
?>

<div>
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    @php
        $data = $this->getData();
    @endphp
    <div class="desktop-wrapper">
        @include('panel.template.dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Dashboard Pengelola</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search"><i class="bi bi-search si"></i><input type="text"
                            placeholder="Cari nasabah, setoran..."></div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end">
                            <div class="w-uname">Budi Santoso</div>
                            <div class="w-urole">Pengelola Bank Sampah</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>

            <!-- W-PAGE: Harga Sampah -->
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Kategori</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                        data-bs-target="#wm-tambah-kategori"><i class="bi bi-plus-circle me-1"></i>Tambah
                        Kategori</button>
                </div>
                <div class="w-panel">
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
                                        <button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-nasabah">
                                            Detail
                                        </button>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
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
                            <span wire:loading.remove wire:target="createCategory">Tambah</span>
                            <span wire:loading wire:target="createCategory">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @script
        <script>
            $wire.on('close-modal', () => {
                const el = document.getElementById('wm-tambah-kategori');
                if (el) bootstrap.Modal.getInstance(el)?.hide();

                // Tutup bottom sheet mobile
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
