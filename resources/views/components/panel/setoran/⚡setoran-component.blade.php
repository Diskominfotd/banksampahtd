<?php

use Livewire\Component;
use App\Services\SetoranService;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;
    use TraitComponent;
    protected SetoranService $setoranService;

    public ?string $keyword = '';
    public ?string $date = '';
    public array $detailItems = [];
    public $perPage = 10;
    public array $editItems = [];

    public int $setoranId;

    public function loadPerpage()
    {
        $this->perPage += 10;
    }
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function boot(SetoranService $setoranService)
    {
        $this->setoranService = $setoranService;
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function hitungSubtotal(int $index): void
    {
        $berat = (float) ($this->detailItems['items'][$index]['berat'] ?? 0);
        $harga = (float) ($this->detailItems['items'][$index]['harga'] ?? 0);
        $this->detailItems['items'][$index]['sub_total'] = $berat * $harga;

        $this->detailItems['total_berat'] = collect($this->detailItems['items'])->sum('berat');
        $this->detailItems['total_saldo'] = collect($this->detailItems['items'])->sum('sub_total');
    }
    public function updatedDetailItems($value, $key)
    {
        if (str_ends_with($key, '.berat')) {
            preg_match('/items\.(\d+)\.berat/', $key, $m);
            if (isset($m[1])) {
                $index = (int) $m[1];
                $berat = $this->detailItems['items'][$index]['berat'] ?? null;
                if ($berat === '' || $berat === null || !is_numeric($berat)) {
                    $this->detailItems['items'][$index]['berat'] = 0;
                }

                $this->hitungSubtotal($index);
            }
        }
    }
    public function detailSetoran(string $setoranId)
    {
        $setoranId = decrypt($setoranId);
        $item = $this->setoranService->getSetoranByIdNasabah($setoranId);
        $this->setoranId = $setoranId;
        $this->detailItems = $item->toArray();
    }
    public function detailEdit(string $setoranId)
    {
        $setoranId = decrypt($setoranId);
        $setoran = $this->setoranService->getSetoranByIdNasabah($setoranId);
        $this->setoranId = $setoranId;
        $this->detailItems = [
            'kode' => $setoran->kode,
            'items' => $setoran->items
                ->map(
                    fn($d) => [
                        'id' => $d->id,
                        'trash' => ['nama' => $d->trash->nama],
                        'harga' => $d->harga ?? 0,
                        'berat' => $d->berat ?? 0,
                        'sub_total' => $d->sub_total,
                    ],
                )
                ->toArray(),
            'total_berat' => $setoran->total_berat ?? 0,
            'total_saldo' => $setoran->total_saldo ?? 0,
            'admin' => optional($setoran->admin)->only('name'),
        ];
    }

    public function editSetoran()
    {
        $this->validate([
            'detailItems.items.*.berat' => ['required', 'numeric', 'min:0.1'],
        ]);

        $items = collect($this->detailItems['items'])
            ->map(
                fn($item) => [
                    'id' => $item['id'],
                    'berat' => $item['berat'],
                ],
            )
            ->toArray();
        $this->setoranService->editSetoran($this->setoranId, ['items' => $items]);
        $this->alertPopUp();
        $this->dispatch('close-modal');
    }

    #[On('doDelete')]
    public function delete(string $setoranId)
    {
        $this->setoranId = decrypt($setoranId);
        $this->setoranService->deleteSetoran($this->setoranId);
        $this->alert();
    }

    public function getData()
    {
        $builder = $this->setoranService->getSetoranByUnit();
        if ($this->keyword) {
            $builder->where(function ($q) {
                $q->whereHas('penyetor.bukutabungans', function ($q) {
                    $q->where('nomor_rekening', 'like', "%{$this->keyword}%");
                })->orWhereHas('penyetor', function ($q) {
                    $q->where('name', 'like', "%{$this->keyword}%");
                });
            });
            $this->resetPage();
        }
        if ($this->date) {
            $builder->whereDate('created_at', $this->date);
        }
        $setoran = $builder->latest()->paginate($this->perPage);
        return [
            'setoran' => $setoran,
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
    {{-- We must ship. - Taylor Otwell --}}
    @php
        $data = $this->getData();
    @endphp
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Daftar Setoran</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('pencarian')">
                    <i class="bi bi-search"></i>
                </div>
                @if (Auth::user()->hasRole(['admin']))
                    <div class="m-gear"
                        style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                        wire:click="movePage('setoran.catat')"><i class="bi bi-plus-lg"></i></div>
                @endif
            </div>
        </div>
        <div class="m-body mb-5" style="padding-top:16px">
            <div class="d-flex flex-column gap-2">
                @if (count($data['setoran']) > 0)
                    @foreach ($data['setoran'] as $st)
                        <div class="tx-card d-flex align-items-start gap-2 fade-up">
                            <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)"><i
                                    class="bi bi-recycle" style="font-size:14px"></i></div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="tx-name text-truncate">{{ ucfirst($st->penyetor->name) }} —
                                    {{ number_format($st->total_berat, 1, ',', '.') }} Kg</div>
                                <div class="tx-date"><i class="bi bi-clock me-1"></i>
                                    {{ $st->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                    {{ $st->bukutabungan->bank->nama }}
                                </div>
                                <div class="d-flex gap-1 mt-2">
                                    <span class="bs bs-green flex-shrink-0">
                                        Rp {{ number_format($st->total_saldo, 0, ',', '.') }}
                                    </span>
                                    <span class="bs bs-purple flex-shrink-0">
                                        Petugas - {{ ucfirst($st->admin->name) }}
                                    </span>

                                </div>
                                <div class="d-flex gap-1 mt-2">
                                    <button @click="$store.sheet.show('detail-setoran')"
                                        wire:click="detailSetoran('{{ encrypt($st->id) }}')" class="btn-tx"> <i
                                            class="bi bi-eye"></i>
                                    </button>
                                    <button @click="$store.sheet.show('edit-setoran')"
                                        wire:click="detailEdit('{{ encrypt($st->id) }}')" class="btn-tx"> <i
                                            class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button"
                                        x-on:click="Swal.fire({
                                        title: 'Hapus Data Setoran?',
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
                                        Livewire.dispatch('doDelete', { setoranId: '{{ encrypt($st->id) }}' })
                                        }
                                        })"
                                        class="btn-tx"> <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="item-empty">
                        <i class="bi bi-inbox"></i>
                        Tidak Ada Data
                    </div>
                @endif
                </tbody>
            </div>
            @if ($data['setoran']->count() >= 10)
                <button type="button" wire:click="loadPerpage"
                    style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:var(--cyan);margin-top:8px;">
                    <span wire:loading.remove wire:target="loadPerpage">Tampilkan lebih banyak</span>
                    <span wire:loading wire:target="loadPerpage">
                        <span class="spinner-border spinner-border-sm"
                            style="width:12px;height:12px;border-width:1.5px;"></span>
                    </span>
                </button>
            @endif
        </div>
        @include('components.⚡mobile-bottombar')
    </div>

    {{-- ======= MOBILE SHEET: Detail Setoran ======= --}}
    <div x-show="$store.sheet.is('detail-setoran')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('detail-setoran')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;
       background:var(--bg-card,#fff);border-radius:20px 20px 0 0;
       padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;
            margin-bottom:18px;color:var(--text-main)">
            Detail Setoran - {{ $detailItems['kode'] ?? 'STR-XXX-XXX-XXX' }}
        </div>
        <div wire:loading.flex wire:target="detailSetoran" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <div class="d-flex flex-column gap-2">
            @foreach ($detailItems['items'] ?? [] as $index => $di)
                <div class="list-item fade-up"><span class="list-num">{{ $index + 1 }}</span>
                    <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">{{ $di['trash']['nama'] }} —
                            {{ number_format($di['berat'], 1, ',', '.') }} Kg</div>
                        <div class="list-sub">Rp. {{ number_format($di['harga'], 0, ',', '.') }}
                        </div>
                    </div><span class="bs bs-green" style="cursor:pointer">Rp.
                        {{ number_format($di['sub_total'], 0, ',', '.') }}</span>

                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-end mt-4">
            <b> Total - Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}</b>
        </div>
    </div>
    {{-- ======= MOBILE SHEET: Pencarian ======= --}}
    <div x-show="$store.sheet.is('pencarian')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('pencarian')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;
       background:var(--bg-card,#fff);border-radius:20px 20px 0 0;
       padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;
            margin-bottom:18px;color:var(--text-main)">
            Pencarian
        </div>
        <div class="f-group"><label>Keyword</label>
            <input class="f-input" type="text" wire:model.live="keyword"
                placeholder="Cari no rekening, nama nasabah..."P>
        </div>
        <div class="f-group"><label>Tanggal</label>
            <input class="f-input" type="date" wire:model.live="date">
        </div>
    </div>
    {{-- ======= MOBILE SHEET: Edit Setoran ======= --}}
    <div x-show="$store.sheet.is('edit-setoran')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('edit-setoran')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Edit - {{ $detailItems['kode'] ?? 'STR-XXX-XXX-XXX' }}
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <div wire:loading.flex wire:target="detailEdit,hitungSubtotal,editSetoran"
                class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($detailItems['items'] ?? [] as $index => $di)
                    <div class="list-item fade-up" wire:key="setoran-item-mobile-{{ $index }}">
                        <span class="list-num">{{ $index + 1 }}</span>
                        <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>

                        <div class="list-main">
                            <div class="list-name">{{ $di['trash']['nama'] }}</div>
                            <div class="list-sub">Rp. {{ number_format($di['harga'], 0, ',', '.') }} / KG</div>
                        </div>

                        <div style="width:100px">
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.1" min="0"
                                    class="form-control @error("detailItems.items.$index.berat") is-invalid @enderror"
                                    style="font-size:12px"
                                    wire:model.live.debounce.400ms="detailItems.items.{{ $index }}.berat"
                                    wire:change="hitungSubtotal({{ $index }})">
                                <span class="input-group-text" style="font-size:11px">KG</span>
                            </div>
                            @error("detailItems.items.$index.berat")
                                <div class="invalid-feedback d-block" style="font-size:10px">{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="margin-top:-6px;margin-bottom:4px">
                        <span class="bs bs-green">Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <span style="font-size:12px;color:var(--text-muted,#888)">
                    Total {{ number_format($detailItems['total_berat'] ?? 0, 1, ',', '.') }} KG
                </span>
                <b>Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}</b>
            </div>

            <div style="font-size:11px;color:var(--text-muted,#888);margin-top:4px">
                Petugas - <strong>{{ ucfirst(data_get($detailItems, 'admin.name', '-')) }}</strong>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="button" class="btn-outline w-100" style="width:100%" @click="$store.sheet.hide()">
                    Batal
                </button>
                <button type="button" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                    wire:click="editSetoran" wire:target="editSetoran">
                    <span wire:loading.remove wire:target="editSetoran">
                        Simpan Perubahan
                    </span>
                    <span wire:loading wire:target="editSetoran">Loading...</span>
                </button>
            </div>
        </div>

    </div>

    {{-- ======= DEKSTOP ======= --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <!-- W-PAGE: Setoran -->
            <div id="w-setoran" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Manajemen Setoran
                        </div>
                        <div style="font-size:11px;color:var(--muted)">342 kg masuk hari ini — 5 setoran pending</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if (Auth::user()->hasRole(['admin']))
                            <button class="w-btn w-btn-primary" style="font-size:11px"
                                wire:click="movePage('setoran.catat')">
                                <i class="bi bi-plus-lg me-1"></i>
                                Catat Setoran
                            </button>
                        @endif
                    </div>
                </div>
                <div class="w-panel">
                    <div class="d-flex gap-2 mb-3">
                        <div class="w-search flex-grow-1">
                            <i class="bi bi-search si"></i>
                            <input type="text" wire:model.live="keyword"
                                placeholder="Cari rekening atau nama nasabah..." style="width:100%">
                        </div>
                        <div class="d-flex gap-1">
                            <input class="w-form-input" type="date" wire:model.live='date' />
                        </div>
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nasabah</th>
                                <th>Nomor Rekening</th>
                                <th>Berat</th>
                                <th>Nilai</th>
                                <th>Unit Setor</th>
                                <th>Waktu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data['setoran']) > 0)
                                @foreach ($data['setoran'] as $index => $st)
                                    <tr>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $data['setoran']->firstItem() + $index }}
                                        </td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $st->kode ?? '-' }}
                                        </td>
                                        <td>
                                            <div style="font-size:11px;font-weight:600">
                                                {{ ucfirst($st->penyetor->name) }}
                                            </div>
                                        </td>
                                        <td><span class="bs bs-ok">
                                                {{ $st->bukutabungan->nomor_rekening }}
                                            </span>
                                        </td>
                                        <td style="font-weight:600">
                                            {{ number_format($st->total_berat, 1, ',', '.') }} kg
                                        </td>
                                        <td style="font-weight:700;color:var(--cyan)">Rp
                                            {{ number_format($st->total_saldo, 0, ',', '.') }}
                                        </td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $st->bukutabungan->bank->nama }}
                                        </td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $st->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                        </td>
                                        <td>
                                            <button wire:click="detailSetoran('{{ encrypt($st->id) }}')"
                                                class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                                data-bs-toggle="modal" data-bs-target="#wm-detail-setoran">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            @if (Auth::user()->hasRole('admin'))
                                                <button wire:click="detailEdit('{{ encrypt($st->id) }}')"
                                                    class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                                    data-bs-toggle="modal" data-bs-target="#wm-edit-setoran">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button"
                                                    x-on:click="Swal.fire({
                                                    title: 'Hapus Data Setoran?',
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
                                                        Livewire.dispatch('doDelete', { setoranId: '{{ encrypt($st->id) }}' })
                                                    }
                                                    })"
                                                    class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"
                                        style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                        <i class="bi bi-inbox"
                                            style="font-size:24px;display:block;margin-bottom:6px"></i>
                                        Tidak Ada Data
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $data['setoran']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ======= MODAL DESKTOP: DETAIL Setoran ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-detail-setoran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Setoran - {{ $detailItems['kode'] ?? 'STR-XXX-XXX-XXX' }}</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body">
                    <div wire:loading.flex wire:target="detailSetoran"
                        class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Sampah</th>
                                <th>Harga</th>
                                <th>Berat</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailItems['items'] ?? [] as $index => $di)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-size:11px;font-weight:600">{{ $di['trash']['nama'] }}</td>
                                    <td>Rp. {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($di['berat'], 1, ',', '.') }} KG</td>
                                    <td>Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td>
                                    <strong>
                                        {{ number_format($detailItems['total_berat'] ?? 1, 1, ',', '.') }}
                                        KG
                                    </strong>
                                </td>
                                <td>
                                    <strong>
                                        Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Petugas -
                                    <strong>{{ ucfirst(data_get($detailItems, 'admin.name', '-')) }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="w-modal-footer">
                </div>
            </div>
        </div>
    </div>
    {{-- ======= MODAL DESKTOP: FORM EDIT Setoran ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-edit-setoran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Edit Setoran - {{ $detailItems['kode'] ?? 'STR-XXX-XXX-XXX' }}</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body" style="position:relative">
                    <div wire:loading.flex wire:target="detailEdit,hitungSubtotal,editSetoran"
                        class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>

                    <form wire:submit.prevent="simpanSetoran">
                        <table class="w-tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Sampah</th>
                                    <th>Harga</th>
                                    <th style="width:130px">Berat</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailItems['items'] ?? [] as $index => $di)
                                    <tr wire:key="setoran-item-{{ $index }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td style="font-size:11px;font-weight:600">
                                            {{ $di['trash']['nama'] }}
                                        </td>
                                        <td>Rp. {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.1" min="0"
                                                    class="form-control @error("detailItems.items.$index.berat") is-invalid @enderror"
                                                    wire:model.live.debounce.400ms="detailItems.items.{{ $index }}.berat"
                                                    wire:change="hitungSubtotal({{ $index }})">
                                                <span class="input-group-text">KG</span>
                                            </div>
                                            @error("detailItems.items.$index.berat")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td>
                                        <strong>
                                            {{ number_format($detailItems['total_berat'] ?? 0, 1, ',', '.') }}
                                            KG
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>
                                            Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        Petugas -
                                        <strong>{{ ucfirst(data_get($detailItems, 'admin.name', '-')) }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
                <div class="w-modal-footer">
                    <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                        wire:loading.attr="disabled" wire:target="editSetoran">Batal</button>

                    <button type="button" wire:click="editSetoran" class="w-btn w-btn-primary"
                        wire:loading.attr="disabled" wire:target="editSetoran">
                        <span wire:loading.remove wire:target="editSetoran">Simpan Perubahan</span>
                        <span wire:loading wire:target="editSetoran">Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('close-modal', () => {
                Alpine.store('sheet').hide();
                $('#wm-edit-setoran').modal('hide');
            });
        </script>
    @endscript
</div>
