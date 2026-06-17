<?php

use Livewire\Component;
use App\Services\TransaksiService;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Livewire\TraitComponent;

new class extends Component {
    use WithPagination;
    protected TransaksiService $transaksiService;
    public function boot(TransaksiService $transaksiService)
    {
        $this->transaksiService = $transaksiService;
    }

    public function getData()
    {
        $builder = $this->transaksiService->getTransaksis();
        $nasabah = $builder->latest()->paginate(10);
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
                                    <td>{{ $nasabah->tanggal_transaksi }}</td>


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
