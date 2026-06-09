<?php

use Livewire\Component;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    protected UserServices $userService;
    public ?string $nama = '';
    public ?string $namaKategori = '';
    public ?string $categoryId = '';

    public ?string $keyword = '';

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
    public function detail(string $id)
    {
        $id = decrypt($id);
        $item = $this->userService->categoryById($id);
        $this->namaKategori = $item->name;
        $this->categoryId = $item->id;
    }
    public function editCategory()
    {
        $this->validate([
            'namaKategori' => 'required|string|max:50|unique:categories,name,' . $this->categoryId,
        ]);
        $this->userService->updateCategory(
            [
                'name' => $this->namaKategori,
            ],
            $this->categoryId,
        );
        $this->reset('namaKategori');
        $this->dispatch('close-modal');
    }

    #[On('doDelete')]
    public function delete()
    {
        $this->userService->delete($this->categoryId);
    }

    public function alertDelete(string $categoryId)
    {
        $this->categoryId = decrypt($categoryId);

        $this->js(
            <<<JS
                Swal.fire({
                title: "Hapus",
                text: "Apakah Anda Yakin ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Batal"
                }).then((result) => {
                   if (result.isConfirmed) {
                Livewire.dispatch('doDelete');
                }
                });
            JS
            ,
        );
    }

    public function getData()
    {
            $categoriyBuilder = $this->userService->categoriesBuilder();
        if ($this->keyword) {
            $categoriyBuilder->where('name', 'LIKE', '%' . $this->keyword . '%');
        }
        $categories = $categoriyBuilder->latest()->paginate(10);
        return [
            'categories' => $categories,
        ];
    }
};
?>

<div x-data x-init="if (!Alpine.store('sheet')) {
    Alpine.store('sheet', {
        active: null,
        show(name) { this.active = name },
        hide() { this.active = null },
        is(name) { return this.active === name },
    })
}">
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    @php
        $data = $this->getData();
    @endphp
    {{-- ======= MOBILE ======= --}}
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back">
                <i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Data Kategori</div>
            <div class="ms-auto">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-kategori')">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
        </div>

        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama kategori...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['categories'] as $index => $category)
                    <div class="list-item fade-up">
                        <div class="list-ico ic1">
                            <i class="bi bi-grid-fill" style="font-size:12px"></i>
                        </div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($category->name) }}</div>
                        </div>
                        <button wire:click="detail('{{ encrypt($category->id) }}')" class="btn-tx"
                            @click="$store.sheet.show('edit-kategori')"> <i
                                class="bi bi-pencil-square me-1"></i>Edit</button>
                        <button wire:click="alertDelete('{{ encrypt($category->id) }}')" class="btn-tx"> <i
                                class="bi bi-trash me-1"></i>Hapus</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — backdrop ======= --}}
    <div x-show="$store.sheet.is('tambah-kategori')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('tambah-kategori')" x-transition:enter="transition ease-out duration-300"
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
        <form wire:submit="createCategory">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="Plastik">
                @error('nama')
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
    <div x-show="$store.sheet.is('edit-kategori')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak></div>

    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('edit-kategori')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>

        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Edit Kategori
        </div>
        <form wire:submit="editCategory">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="namaKategori" placeholder="Plastik">
                @error('namaKategori')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="editCategory">
                    <span wire:loading.remove wire:target="editCategory">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="editCategory">Loading...</span>
                </button>
            </div>
        </form>
    </div>

    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
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
                                        <button wire:click="detail('{{ encrypt($category->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-edit-kategori">
                                            Edit
                                        </button>
                                        <button wire:click="alertDelete('{{ encrypt($category->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                            Hapus
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
    <div wire:ignore.self class="modal fade" id="wm-edit-kategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Kategori</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editCategory">
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
                            <span wire:loading.remove wire:target="editCategory">Tambah</span>
                            <span wire:loading wire:target="editCategory">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @script
        <script>
            $wire.on("close-modal", () => {
                $('#wm-tambah-kategori').modal('hide');
                $('#wm-edit-kategori').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
