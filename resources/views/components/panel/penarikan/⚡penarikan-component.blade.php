<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Very little is needed to make a happy life. - Marcus Aurelius --}}
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
                            {{-- @foreach ($data['categories'] as $index => $category)
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
                            @endforeach --}}
                        </tbody>
                    </table>
                    {{-- {{ $data['categories']->links('vendor.pagination.bootstrap-5') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
