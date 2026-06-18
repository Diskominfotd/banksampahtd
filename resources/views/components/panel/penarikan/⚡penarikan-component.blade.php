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
        $nasabah = $builder->latest()->paginate($this->perPage);
        return [
            'nasabah' => $nasabah,
        ];
    }
};
?>

<div>
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
            <div class="ms-auto">
                <div wire:click="movePage('buat.penarikan.saldo')" class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
        </div>
        <div class="m-body">
            <div class="m-search mb-3 mt-3">
                <i class="bi bi-search si"></i>
                <input wire:model.live="keyword" type="text" placeholder="Cari nama nasabah, unit...">
            </div>
            <div class="d-flex flex-column gap-2 mt-2">
                @foreach ($data['nasabah'] as $index => $nasabah)
                    <div class="list-item fade-up"><span class="list-num">{{ $index + 1 }}</span>
                        <div class="list-ico ic1"><i class="bi bi-cash" style="font-size:12px"></i></div>
                        <div class="list-main">
                            <div class="list-name">{{ ucfirst($nasabah->owner->name) }} —
                                {{ $nasabah->owner->bukutabungans->first()->nomor_rekening }}</div>
                            <div class="list-sub">{{ $nasabah->owner->bukutabungans->first()->bank->nama }} ·
                                {{ $nasabah->tanggal_transaksi->diffForHumans() }}
                            </div>
                            <div class="list-sub">
                                <b>Sisa - Rp {{ number_format($nasabah->sisa_saldo ?? 0, 0, ',', '.') }} </b>
                            </div>
                        </div><span class="bs bs-green" style="cursor:pointer"
                            onclick="openDetail('m-detail-setoran')">Rp
                            {{ number_format($nasabah->total_penarikan ?? 0, 0, ',', '.') }}</span>
                    </div>
                @endforeach
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
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Daftar Transaksi</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                    <a class="w-btn w-btn-primary" href="{{ route('buat.penarikan.saldo') }}" style="font-size:11px"><i
                            class="bi bi-plus-circle me-1"></i>Buat
                        Penarikan
                    </a>
                </div>
                <div class="w-panel">
                    <div class="w-search mb-3" style="width:100%">
                        <i class="bi bi-search si"></i>
                        <input wire:model.live="keyword" type="text" placeholder="Cari nama nasabah, unit..."
                            style="width:100%">
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>No. Rekening</th>
                                <th>Total Penarikan</th>
                                <th>Sisa Saldo</th>
                                <th>Unit</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['nasabah'] as $index => $nasabah)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">{{ $index + 1 }}</td>
                                    <td style="font-weight:600">{{ ucfirst($nasabah->owner->name) }}</td>
                                    <td><span class="bs bs-ok">
                                            {{ $nasabah->owner->bukutabungans->first()->nomor_rekening }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($nasabah->total_penarikan ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($nasabah->sisa_saldo ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $nasabah->owner->bukutabungans->first()->bank->nama }}</td>
                                    <td>{{ $nasabah->tanggal_transaksi->diffForHumans() }}</td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data['nasabah']->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
