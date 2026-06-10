<?php

use Livewire\Component;
use App\Services\TrashServices;
use App\Models\Price;
use App\Models\BankSampah;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\On;
new class extends Component {
    use WithPagination;
    protected TrashServices $trashService;

    // new jenis attribut
    public ?int $kategori;
    public string $nama = '';
    public string $syarat = '';
    public ?int $harga = null;

    // update jenis attribut'
    public ?int $kategoriJenis;
    public ?string $namaJenis;
    public ?string $syaratJenis;
    public ?string $jenisId;

    //search
    public ?string $keyword = '';

    //Price
    public array $prices = [];

    public ?string $trashId;

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
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
        $this->dispatch('close-modal');
    }

    public function detailJenis(string $id)
    {
        $id = decrypt($id);
        $item = $this->trashService->getTrashById($id);
        $this->kategoriJenis = $item->category->id;
        $this->namaJenis = $item->nama;
        $this->syaratJenis = $item->syarat;
        $this->jenisId = $item->id;
    }

    public function editJenis()
    {
        $rules = [
            'kategoriJenis' => 'required|exists:categories,id',
            'namaJenis' => 'required|string|max:100',
            'syaratJenis' => 'required|string|max:100',
        ];
        $this->validate($rules);
        $this->trashService->updateJenis(
            [
                'category_id' => $this->kategoriJenis,
                'nama' => $this->namaJenis,
                'syarat' => $this->syaratJenis,
            ],
            $this->jenisId,
        );
        $this->reset(['kategoriJenis', 'namaJenis', 'syaratJenis']);
        $this->dispatch('close-modal');
    }

    public function priceDetail()
    {
        $bank = Auth::user()->unit;

        $this->prices = $this->trashService
            ->priceList()
            ->map(
                fn($price) => [
                    'id' => $price->id,
                    'label' => $price->trash->nama,
                    'value' => $price->harga,
                    'is_induk' => !Price::where('trash_id', $price->trash_id)
                    ->where('bank_id', $bank->id)->exists(),
                ],
            )
            ->toArray();
    }
    // Livewire
    public function updatePrice($index)
    {
        $price = $this->prices[$index];
        $this->trashService->updatePrice($price['id'], [
            'value' => $price['value'],
            'is_induk' => $price['is_induk'],
        ]);
        $this->priceDetail();
    }
    #[On('doDelete')]
    public function delete()
    {
        $this->trashService->deleteTrash($this->trashId);
    }

    public function alertDelete(string $trashId)
    {
        $this->trashId = decrypt($trashId);
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
        $categories = $this->trashService->categoryBuilder()->get();
        $trashsBuilder = $this->trashService->priceAndTrashList();
        if ($this->keyword) {
            $trashsBuilder->whereHas('trash', function ($query) {
                $query->where('nama', 'like', '%' . $this->keyword . '%');
            });
            $this->resetPage();
        }
        $trashs = $trashsBuilder->latest()->paginate(10);
        return [
            'categories' => $categories,
            'trashs' => $trashs,
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
    {{-- The only way to do great work is to love what you do. - Steve Jobs --}}
    @php
        $data = $this->getData();
    @endphp

    <div id="m-harga">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Harga Sampah</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('tambah-jenis')">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('update-harga')"><i class="bi bi-pencil-fill" style="font-size:12px"></i>
                </div>
            </div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama jenis sampah...">
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['trashs'] as $t)
                    <div class="list-item fade-up">
                        <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($t->trash->nama) }}</div>
                            <div class="list-sub">{{ ucfirst($t->trash->syarat) }}</div>
                            <div class="list-sub"><b>{{ ucfirst($t->trash->category->name) }}</b></div>
                            <div class="d-flex gap-1 mt-2">
                                <button @click="$store.sheet.show('update-jenis')"
                                    wire:click="detailJenis('{{ encrypt($t->trash->id) }}')" class="btn-tx">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button wire:click="alertDelete('{{ encrypt($t->id) }}')" class="btn-tx"><i
                                        class="bi bi-trash-fill"></i></button>
                            </div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--cyan)">
                                Rp {{ number_format($t->harga ?? 0, 0, ',', '.') }}</div>
                            <div style="font-size:9px;color:var(--muted)">/kg</div>
                            <div style="font-size:9px;color:var(--muted)"><b>Harga - {{ ucfirst($t->type) }}</b></div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        <nav class="m-bottom-nav">
            <a class="m-nav-btn" data-page="m-beranda"><i class="bi bi-house-fill"></i><span>Beranda</span></a>
            <a class="m-nav-btn" data-page="m-setoran"><i class="bi bi-recycle"></i><span>Setoran</span></a>
            <a class="m-nav-btn" data-page="m-nasabah"><i class="bi bi-people-fill"></i><span>Nasabah</span></a>
            <a class="m-nav-btn" data-page="m-profil"><i class="bi bi-person-fill"></i><span>Profil</span></a>
        </nav>
    </div>
    <div x-show="$store.sheet.is('update-harga')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    {{-- ======= BOTTOM SHEET MOBILE — konten + form ======= --}}
    <div x-show="$store.sheet.is('update-harga')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Update Harga Beli
        </div>
        @foreach ($prices as $index => $price)
            <div wire:loading.flex wire:target="priceDetail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            <div class="col-12">
                <label class="f-form-label">{{ $price['label'] }} (Rp/kg)</label>
                <div class="d-flex gap-2 mb-1 align-items-center" x-data="{
                    formatted: '',
                    init() {
                        this.syncFromWire();
                        $watch(() => $wire.prices[{{ $index }}]?.value, () => {
                            this.syncFromWire();
                        });
                    },
                    syncFromWire() {
                        let val = $wire.prices[{{ $index }}]?.value;
                        if (!val) {
                            this.formatted = '';
                            return;
                        }
                        this.formatted = 'Rp ' + Number(val).toLocaleString('id-ID');
                    },
                    format(val) {
                        let num = val.replace(/\D/g, '');
                        if (!num) {
                            this.formatted = '';
                            $wire.set('prices.{{ $index }}.value', null);
                            return;
                        }
                        let number = parseInt(num, 10);
                        this.formatted = 'Rp ' + number.toLocaleString('id-ID');
                        $wire.set('prices.{{ $index }}.value', number);
                    }
                }" x-init="init()">
                    <input class="f-input"type="text" x-model="formatted" @input="format($event.target.value)"
                        wire:ignore :disabled="$wire.prices[{{ $index }}]?.is_induk">
                    <button type="button" title="Ubah"
                        style="width:28px;height:28px;flex-shrink:0;border-radius:50%;background:none;border:0.5px solid #198754;color:#198754;display:flex;align-items:center;justify-content:center;padding:0;"
                        wire:click="updatePrice({{ $index }})" wire:loading.attr="disabled"
                        wire:target="updatePrice({{ $index }})">
                        <span wire:loading.remove wire:target="updatePrice({{ $index }})">
                            <i class="bi bi-check2" style="font-size:14px;"></i>
                        </span>
                        <span wire:loading wire:target="updatePrice({{ $index }})">
                            <span class="spinner-border spinner-border-sm" role="status"
                                style="width:12px;height:12px;border-width:1.5px;"></span>
                        </span>
                    </button>
                </div>
                @if (Auth::user()->unit->parent_id)
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="harga_induk_{{ $index }}"
                            wire:model="prices.{{ $index }}.is_induk">

                        <label class="form-check-label text-muted small" for="harga_induk_{{ $index }}">
                            Gunakan harga induk
                        </label>
                    </div>
                @endif
            </div>
        @endforeach
        <div class="d-flex gap-2 mt-2">

        </div>
    </div>
    <div x-show="$store.sheet.is('tambah-jenis')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('tambah-jenis')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Tambah Jenis Sampah
        </div>
        <form wire:submit="newJenis">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="nama" placeholder="Nama Jenis Sampah">
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Syarat</label>
                <input class="f-input" type="text" wire:model="syarat" placeholder="Syarat Jenis Sampah">
                @error('syarat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Kategori</label>
                <select class="f-input" wire:model="kategori">
                    <option hidden>Pilih Kategori</option>
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
            <div class="f-group" x-data="{
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
                <label>Harga/KG</label>
                <input class="f-input" type="text" placeholder="Rp 0" maxlength="20" x-model="formatted"
                    @input="format($event.target.value)" wire:ignore>
                @error('harga')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="newJenis">
                    <span wire:loading.remove wire:target="newJenis">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="newJenis">Loading...</span>
                </button>
            </div>
        </form>
    </div>
    <div x-show="$store.sheet.is('update-jenis')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('update-jenis')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Update Jenis Sampah
        </div>
        <div wire:loading.flex wire:target="detailJenis" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <form wire:submit="editJenis">
            <div class="f-group">
                <label>Nama</label>
                <input class="f-input" type="text" wire:model="namaJenis" placeholder="Nama Jenis Sampah">
                @error('namaJenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Syarat</label>
                <input class="f-input" type="text" wire:model="syaratJenis" placeholder="Syarat Jenis Sampah">
                @error('syaratJenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="f-group">
                <label>Kategori</label>
                <select class="f-input" wire:model="kategoriJenis">
                    <option hidden>Pilih Kategori</option>
                    @foreach ($data['categories'] as $category)
                        <option value="{{ $category->id }}">
                            {{ ucfirst($category->name) }}
                        </option>
                    @endforeach
                </select>
                @error('kategoriJenis')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-outline w-100" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="submit" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:target="editJenis">
                    <span wire:loading.remove wire:target="editJenis">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="newJenis">Loading...</span>
                </button>
            </div>
        </form>
    </div>


    {{-- ======= Dekstop ======= --}}
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
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Harga Beli
                            Sampah</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="w-btn w-btn-primary" style="font-size:11px" data-bs-toggle="modal"
                            data-bs-target="#wm-tambah-jenis">
                            <i class="bi bi-patch-plus me-1"></i>Tambah Jenis
                        </button>

                        <button wire:click="priceDetail" class="w-btn w-btn-primary" style="font-size:11px"
                            data-bs-toggle="modal" data-bs-target="#wm-update-harga">
                            <i class="bi bi-pencil-fill me-1"></i>Update Harga
                        </button>
                    </div>
                </div>
                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%">
                        <i class="bi bi-search si"></i>
                        <input wire:model.live="keyword" type="text" placeholder="Cari jenis sampah..."
                            style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jenis Sampah</th>
                                <th>Syarat</th>
                                <th>Harga/kg</th>
                                <th>Type Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['trashs'] as $t)
                                <tr>
                                    <td><span class="bs bs-ok">{{ ucfirst($t->trash->category->name) }}</span></td>
                                    <td style="font-size:11px;font-weight:600">{{ ucfirst($t->trash->nama) }}</td>
                                    <td style="font-size:10px;color:var(--muted)">{{ ucfirst($t->syarat) }}</td>
                                    <td style="font-family:'Syne',sans-serif;font-weight:700;color:var(--cyan)">
                                        Rp {{ number_format($t->harga ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ ucfirst($t->type) }}
                                    </td>
                                    <td>
                                        <button wire:click="detailJenis('{{ encrypt($t->trash->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-edit-jenis">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button wire:click="alertDelete('{{ encrypt($t->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['trashs']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-edit-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Jenis Sampah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="editJenis">
                    <div class="w-modal-body">
                        <div wire:loading.flex wire:target="detailJenis"
                            class="justify-content-center align-items-center"
                            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                            <div class="spinner-border text-success"></div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="w-form-label">Kategori</label>
                                    <select class="w-form-input" wire:model.live="kategoriJenis">
                                        <option value="" hidden>Pilih Kategori</option>
                                        @foreach ($data['categories'] as $category)
                                            <option value="{{ $category->id }}">
                                                {{ ucfirst($category->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategoriJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label class="w-form-label">Nama</label>
                                    <input class="w-form-input" type="text" wire:model="namaJenis"
                                        placeholder="Nama Jenis Sampah" maxlength="100">
                                    @error('namaJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    <label class="w-form-label">Syarat</label>
                                    <input class="w-form-input" type="text" wire:model="syaratJenis"
                                        placeholder="Syarat Jenis Sampah" maxlength="100">
                                    @error('syaratJenis')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="editJenis">Batal</button>
                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editJenis">
                            <span wire:loading.remove wire:target="editJenis">Simpan</span>
                            <span wire:loading wire:target="editJenis">Loading...</span>
                        </button>
                    </div>
                </form>
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
    <div wire:ignore.self class="modal fade" id="wm-update-harga" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Ubah Harga</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="m-search mb-3 ms-2 me-2">
                    <i class="bi bi-search si"></i>
                    <input type="text" wire:model.live="keyword" placeholder="Cari nama jenis sampah...">
                </div>
                <div class="w-modal-body" style="overflow-y: auto; max-height: 60vh;">
                    <div wire:loading.flex wire:target="priceDetail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <div class="row g-3">
                        @foreach ($prices as $index => $price)
                            <div class="col-12">
                                <label class="w-form-label">{{ $price['label'] }} (Rp/kg)</label>
                                <div class="d-flex gap-2 align-items-center" x-data="{
                                    formatted: '',
                                    init() {
                                        this.syncFromWire();
                                        $watch(() => $wire.prices[{{ $index }}]?.value, () => {
                                            this.syncFromWire();
                                        });
                                    },
                                    syncFromWire() {
                                        let val = $wire.prices[{{ $index }}]?.value;
                                        if (!val) {
                                            this.formatted = '';
                                            return;
                                        }
                                        this.formatted = 'Rp ' + Number(val).toLocaleString('id-ID');
                                    },
                                    format(val) {
                                        let num = val.replace(/\D/g, '');
                                        if (!num) {
                                            this.formatted = '';
                                            $wire.set('prices.{{ $index }}.value', null);
                                            return;
                                        }
                                        let number = parseInt(num, 10);
                                        this.formatted = 'Rp ' + number.toLocaleString('id-ID');
                                        $wire.set('prices.{{ $index }}.value', number);
                                    }
                                }"
                                    x-init="init()">
                                    <input class="w-form-input flex-grow-1" type="text" x-model="formatted"
                                        @input="format($event.target.value)" wire:ignore
                                        :disabled="$wire.prices[{{ $index }}]?.is_induk">
                                    <button type="button" title="Ubah"
                                        style="width:28px;height:28px;flex-shrink:0;border-radius:50%;background:none;border:0.5px solid #198754;color:#198754;display:flex;align-items:center;justify-content:center;padding:0;"
                                        wire:click="updatePrice({{ $index }})" wire:loading.attr="disabled"
                                        wire:target="updatePrice({{ $index }})">
                                        <span wire:loading.remove wire:target="updatePrice({{ $index }})">
                                            <i class="bi bi-check2" style="font-size:14px;"></i>
                                        </span>
                                        <span wire:loading wire:target="updatePrice({{ $index }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                style="width:12px;height:12px;border-width:1.5px;"></span>
                                        </span>
                                    </button>
                                </div>
                                @if (Auth::user()->unit->parent_id)
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox"
                                            id="harga_induk_{{ $index }}"
                                            wire:model="prices.{{ $index }}.is_induk">
                                        <label class="form-check-label text-muted small"
                                            for="harga_induk_{{ $index }}">
                                            Gunakan harga induk
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="w-modal-footer">

                </div>
            </div>
        </div>
    </div>
    @script
        <script>
            $wire.on('close-modal', () => {
                $('#wm-tambah-jenis').modal('hide');
                $('#wm-edit-jenis').modal('hide');
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
