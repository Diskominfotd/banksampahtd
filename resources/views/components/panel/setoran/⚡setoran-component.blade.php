<?php

use Livewire\Component;
use App\Services\SetoranService;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    protected SetoranService $setoranService;

    public ?string $keyword = '';

    public array $detailItems = [];

    public function boot(SetoranService $setoranService)
    {
        $this->setoranService = $setoranService;
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function detailSetoran(string $nasabahId)
    {
        $nasabahId = decrypt($nasabahId);
        $item = $this->setoranService->getSetoranByIdNasabah($nasabahId);
        $this->detailItems = $item->toArray();
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
        $setoran = $builder->latest()->paginate(10);
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
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Daftar Setoran</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    wire:click="movePage('setoran.catat')"><i class="bi bi-plus-lg"></i></div>
            </div>
        </div>
        <div class="m-body mb-5" style="padding-top:16px">
            <div class="m-search mb-2"><i class="bi bi-search si">
                </i><input wire:model.live="keyword" type="text" placeholder="Cari no rekening,nama nasabah...">
            </div>
            <div class="m-chips mb-3">
                <button class="chip active">Semua</button>
                <button class="chip">Plastik</button>
                <button class="chip">Kertas</button>
                <button class="chip">Logam</button>
                <button class="chip">Kaca</button>
                <button class="chip">Elektronik</button>
            </div>
            <div class="d-flex flex-column gap-2">
                @foreach ($data['setoran'] as $st)
                    <div class="tx-card d-flex align-items-start gap-2 fade-up"
                        onclick="openDetail('m-detail-setoran')">
                        <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)"><i
                                class="bi bi-recycle" style="font-size:14px"></i></div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="tx-name text-truncate">{{ ucfirst($st->penyetor->name) }} —
                                {{ number_format($st->total_berat, 0, ',', '.') }} Kg</div>
                            <div class="tx-date"><i class="bi bi-clock me-1"></i>
                                {{ $st->created_at->timezone('Asia/Jakarta')->diffForHumans() }} · @foreach ($st->penyetor->bukutabungans as $buku)
                                    {{ $buku->bank->nama }}
                                @endforeach ·
                                <b>Rp {{ number_format($st->total_saldo, 0, ',', '.') }}</b>
                            </div>
                            <div class="d-flex gap-1 mt-2">
                                <button @click="$store.sheet.show('detail-setoran')"
                                    wire:click="detailSetoran('{{ encrypt($st->penyetor->id) }}')" class="btn-tx"> <i
                                        class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2">
                {{ $data['setoran']->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
        @include('panel.template.mobile-bottombar')
    </div>

    {{-- ======= MOBILE SHEET: DETAIL Setoran ======= --}}
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
            Detail Setoran
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
                            {{ number_format($di['berat'], 0, ',', '.') }} Kg</div>
                        <div class="list-sub">Rp. {{ number_format($di['harga'], 0, ',', '.') }} - {{ $di['type'] }}
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
                        <button class="w-btn w-btn-ghost" style="font-size:11px"><i
                                class="bi bi-download me-1"></i>Export</button>
                        <button class="w-btn w-btn-ghost" style="font-size:11px" onclick="openWModal('wm-filter')"><i
                                class="bi bi-funnel me-1"></i>Filter</button>
                        <button class="w-btn w-btn-primary" style="font-size:11px"
                            wire:click="movePage('setoran.catat')"><i class="bi bi-plus-lg me-1"></i>Catat
                            Setoran</button>
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
                            <button class="chip active">Semua</button>
                            <button class="chip">Plastik</button>
                            <button class="chip">Kertas</button>
                            <button class="chip">Logam</button>
                            <button class="chip">Elektronik</button>
                        </div>
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
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
                            @foreach ($data['setoran'] as $index => $st)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ $data['setoran']->firstItem() + $index }}</td>
                                    <td>
                                        <div style="font-size:11px;font-weight:600">{{ ucfirst($st->penyetor->name) }}
                                        </div>
                                    </td>
                                    <td><span class="bs bs-ok">
                                            @foreach ($st->penyetor->bukutabungans as $buku)
                                                {{ $buku->nomor_rekening }}
                                            @endforeach
                                        </span>
                                    </td>
                                    <td style="font-weight:600"> {{ number_format($st->total_berat, 0, ',', '.') }} kg
                                    </td>
                                    <td style="font-weight:700;color:var(--cyan)">Rp
                                        {{ number_format($st->total_saldo, 0, ',', '.') }}
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        @foreach ($st->penyetor->bukutabungans as $buku)
                                            {{ $buku->bank->nama }}
                                        @endforeach
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ $st->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                    </td>
                                    <td>
                                        <button wire:click="detailSetoran('{{ encrypt($st->penyetor->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-setoran">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['setoran']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    {{-- ======= MODAL DESKTOP: DETAIL Setoran ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-detail-setoran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Setoran</div>
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
                                    <td>{{ number_format($di['berat'], 0, ',', '.') }} KG</td>
                                    <td>Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td>
                                    <strong>
                                        {{ number_format($detailItems['total_berat'] ?? 0, 0, ',', '.') }}
                                        KG
                                    </strong>
                                </td>
                                <td>
                                    <strong>
                                        Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="w-modal-footer">
                    {{-- <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                        onclick="setTimeout(()=> new bootstrap.Modal(document.getElementById('wm-tambah-setoran')).show(), 300)">
                        Catat Setoran
                    </button>
                    <button class="w-btn w-btn-danger">Proses Penarikan</button> --}}
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            $wire.on('close-modal', () => {
                Alpine.store('sheet').hide();
            });
        </script>
    @endscript
</div>
