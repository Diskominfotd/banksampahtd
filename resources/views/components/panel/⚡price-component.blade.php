<?php

use Livewire\Component;
use App\Services\TrashServices;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    protected TrashServices $trashService;

    public ?int $kategori;
    public string $nama = '';
    public string $syarat = '';
    public ?int $harga = null;

    public function boot(TrashServices $trashService)
    {
        $this->trashService = $trashService;
    }

    public function newJenis()
    {
        $rules = [
            'kategori' => 'required|exists:categories,id',
            'nama' => 'required|string|max:100',
            'syarat' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
        ];
        $this->validate($rules);
        $this->trashService->createJenis([
            'category_id' => $this->kategori,
            'nama' => $this->nama,
            'syarat' => $this->syarat,
            'harga' => $this->harga,
        ]);
        $this->reset(['kategori', 'nama', 'syarat', 'harga']);
    }

    public function getData()
    {
        $categories = $this->trashService->categoryBuilder()->get();
        $trashs = $this->trashService->getTrashBuilder()->latest()->paginate(10);
        return [
            'categories' => $categories,
            'trashs' => $trashs,
        ];
    }
};
?>

<div>
    {{-- The only way to do great work is to love what you do. - Steve Jobs --}}
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
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Harga Beli
                            Sampah</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                        data-bs-target="#wm-tambah-jenis"><i class="bi bi-patch-plus me-1">
                        </i>Tambah Jenis
                    </button>
                </div>
                <div class="w-panel">
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis Sampah</th>
                                <th>Syarat</th>
                                <th>Harga/kg</th>
                                <th>Perubahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['trashs'] as $t)
                                <tr>
                                    <td><span class="bs bs-ok">{{ ucfirst($t->category->name) }}</span></td>
                                    <td style="font-size:11px;font-weight:600">{{ ucfirst($t->nama) }}</td>
                                    <td style="font-size:10px;color:var(--muted)">{{ ucfirst($t->syarat) }}</td>
                                    <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">
                                        @php $price = $t->prices->first() @endphp
                                        Rp {{ number_format($price?->harga ?? 0, 0, ',', '.') }}
                                        {{ dd($price) }}
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">Stabil</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-tambah-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Daftarkan Jenis Sampah Baru</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="newJenis">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Kategori</label>
                                    <select class="w-form-input" wire:model.live="kategori">
                                        <option value="" hidden>Pilih Kategori</option>
                                        @foreach ($data['categories'] as $category)
                                            <option value="{{ $category->id }}">
                                                {{ ucfirst($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="nama"
                                        placeholder="Nama Jenis Sampah" maxlength="100">
                                    @error('nama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    <label class="w-form-label">Syarat</label>
                                    <input class="w-form-input" type="text" wire:model="syarat"
                                        placeholder="Syarat Jenis Sampah" maxlength="100">
                                    @error('syarat')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div x-data="{
                                    raw: '',
                                    formatted: '',
                                    format(val) {
                                        let num = val.replace(/\D/g, '');
                                        if (!num) {
                                            this.formatted = '';
                                            this.raw = '';
                                            $wire.set('harga', null); // reset ke null kalau kosong
                                            return;
                                        }
                                        let number = parseInt(num, 10);
                                        this.raw = number;
                                        this.formatted = 'Rp ' + number.toLocaleString('id-ID');
                                        $wire.set('harga', number); // sync langsung ke Livewire property
                                    }
                                }">
                                    <label class="w-form-label">Harga/Kg</label>
                                    <input class="w-form-input" type="text" placeholder="Rp 0" maxlength="20"
                                        x-model="formatted" @input="format($event.target.value)" wire:ignore>
                                    {{-- input hidden tidak perlu lagi --}}
                                    @error('harga')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="newJenis">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="newJenis">
                            <span wire:loading.remove wire:target="newJenis">Daftarkan</span>
                            <span wire:loading wire:target="newJenis">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
