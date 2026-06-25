<?php

use Livewire\Component;
use App\Services\TransaksiService;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;

new class extends Component {
    use WithPagination;
    protected TransaksiService $transaksiService;
    public ?string $keyword = '';
    public ?int $perPage = 10;
    public ?string $date = '';
    public $itemTrxDetail = null;

    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
    public function boot(TransaksiService $transaksiService)
    {
        $this->transaksiService = $transaksiService;
    }

    public function movePage(string $route)
    {
        return redirect()->route($route);
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }
    public function trxDetail($id)
    {
        $id = decrypt($id);
        $item = $this->transaksiService->transaksiById($id);
        $this->itemTrxDetail = $item;
    }

    public function getData()
    {
        $builder = $this->transaksiService->getTransaksis();
        if ($this->keyword) {
            $builder->where(function ($q) {
                $q->whereHas('owner', function ($q) {
                    $q->where('name', 'like', "%{$this->keyword}%");
                })->orWhereHas('owner.bukutabungans', function ($q) {
                    $q->where('nomor_rekening', 'like', "%{$this->keyword}%");
                });
            });
            $this->resetPage();
        }
        if ($this->date) {
            $builder->whereDate('created_at', $this->date);
        }
        $nasabah = $builder->latest()->paginate($this->perPage);
        return [
            'nasabah' => $nasabah,
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
    {{-- Very little is needed to make a happy life. - Marcus Aurelius --}}
    <style>
        a {
            text-decoration: none;
        }
    </style>
    @php
        $data = $this->getData();
    @endphp
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Daftar Transaksi</div>
            <div class="ms-auto d-flex gap-2 mb-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('pencarian')">
                    <i class="bi bi-search"></i>
                </div>
                @if (Auth::user()->hasRole(['admin']))
                    <div wire:click="movePage('buat.penarikan.saldo')" class="m-gear"
                        style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="m-body">
            <div class="d-flex flex-column gap-2 mt-2">
                @forelse ($data['nasabah'] as $index => $nasabah)
                    @php
                        $bukutabungan = $nasabah->owner->bukutabungans->first();
                    @endphp
                    <div class="list-item fade-up" wire:click="trxDetail('{{ encrypt($nasabah->id) }}')"
                        @click="$store.sheet.show('detail-trx-id')">

                        <span class="list-num">{{ $index + 1 }}</span>
                        <div class="list-ico ic1"><i class="bi bi-cash" style="font-size:12px"></i></div>
                        <div class="list-main">
                            <div class="list-name">
                                {{ ucfirst($nasabah->owner->name) }} —
                                {{ $bukutabungan?->nomor_rekening ?? '-' }}
                            </div>
                            <div class="list-sub">
                                {{ $bukutabungan?->bank?->nama ?? '-' }} ·
                                {{ $nasabah->tanggal_transaksi->diffForHumans() }}
                            </div>
                            <div class="list-sub">
                                <b>Sisa - Rp {{ number_format($nasabah->sisa_saldo ?? 0, 0, ',', '.') }}</b>
                            </div>
                        </div>

                        <span class="bs bs-green" style="cursor:pointer" onclick="openDetail('m-detail-setoran')">
                            Rp {{ number_format($nasabah->total_penarikan ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="item-empty">
                        <i class="bi bi-inbox"></i>
                        Tidak Ada Data
                    </div>
                @endforelse
            </div>
            @if (count($data['nasabah']) >= 10)
                <button type="button" wire:click="loadMore"
                    style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                    <span wire:loading.remove wire:target="loadMore">Tampilkan lebih banyak</span>
                    <span wire:loading wire:target="loadMore">
                        <span class="spinner-border spinner-border-sm"
                            style="width:12px;height:12px;border-width:1.5px;"></span>
                    </span>
                </button>
            @endif
        </div>
    </div>
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
    {{-- Detail trx id --}}
    <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    {{-- Sheet Detail trx id --}}
    <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Detail Penarikan {{ $itemTrxDetail->kode ?? 'TRX-XXX-XXX-XX' }}
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            @if ($itemTrxDetail)
                <div
                    style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                    <div
                        style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                        Nilai Penarikan</div>
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">Rp
                        {{ number_format($itemTrxDetail->total_penarikan, 0, ',', '.') ?? 0 }}</div>
                </div>
                <div class="detail-field"><span class="df-key">No. Transaksi</span><span
                        class="df-val">{{ $itemTrxDetail->kode ?? '-' }}</span></div>
                <div class="detail-field"><span class="df-key">Nasabah</span>
                    <span class="df-val">{{ ucfirst($itemTrxDetail->owner->name) }}</span>
                </div>
                <div class="detail-field"><span class="df-key">Sisa Saldo</span><span class="df-val">
                        Rp {{ number_format($itemTrxDetail->sisa_saldo, 0, ',', '.') ?? 0 }}
                    </span>
                </div>
                <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                        {{ $itemTrxDetail->created_at->format('Y-m-d') }}
                    </span>
                </div>
                <div class="detail-field"><span class="df-key">Unit</span>
                    <span class="df-val">
                        {{ $itemTrxDetail->bukutabungan->bank->nama }}</span>
                </div>
                <div class="detail-field"><span class="df-key">Petugas</span>
                    <span class="df-val">
                        {{ ucfirst($itemTrxDetail->admin->name) }}
                    </span>
                </div>
            @endif
        </div>
    </div>


    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Transaksi
                        </div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    @if (Auth::user()->hasRole(['admin']))
                        <a class="w-btn w-btn-primary" href="{{ route('buat.penarikan.saldo') }}"
                            style="font-size:11px"><i class="bi bi-plus-circle me-1"></i>Buat
                            Penarikan
                        </a>
                    @endif
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
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>No. Rekening</th>
                                <th>Total Penarikan</th>
                                <th>Sisa Saldo</th>
                                <th>Unit</th>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data['nasabah']) > 0)
                                @foreach ($data['nasabah'] as $index => $nasabah)
                                    <tr>
                                        <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                        <td style="font-size:10px;color:var(--muted)">{{ $nasabah->kode }}</td>
                                        <td style="font-weight:600">{{ ucfirst($nasabah->owner->name) }}</td>
                                        <td><span class="bs bs-ok">
                                                {{ $nasabah->owner->bukutabungans->first()->nomor_rekening }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($nasabah->total_penarikan ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($nasabah->sisa_saldo ?? 0, 0, ',', '.') }}</td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $nasabah->owner->bukutabungans->first()->bank->nama }}
                                        </td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ $nasabah->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                        </td>
                                        <td style="font-size:10px;color:var(--muted)">
                                            {{ ucfirst($nasabah->admin->name) ?? '-' }}
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
                    {{ $data['nasabah']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
