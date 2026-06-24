<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-harga" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Buat Transaksi
                            Penarikan Saldo</div>
                        <div style="font-size:11px;color:var(--muted)">Berlaku per 20 Mei 2026 · Diperbarui oleh Admin
                        </div>
                    </div>
                </div>
                <div class="m-2">
                    <div class="w-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div style="font-size:12px;font-weight:700">Item Penarikan</div>
                                <div style="font-size:10px;color:var(--muted)">Tambahkan Nasabah untuk penarikan saldo
                                </div>
                            </div>
                            <button wire:click="getNasabah" data-bs-toggle="modal" data-bs-target="#wm-pilih-nasabah"
                                class="w-btn w-btn-primary" class="w-btn w-btn-primary"
                                style="font-size:11px;width:auto;padding:6px 12px">
                                <i class="bi bi-plus-lg me-1"></i> Pilih Nasabah
                            </button>
                        </div>
                        <table class="w-tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Rekening</th>
                                    <th>Saldo</th>
                                    <th>Jumlah Penarikan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedNasabah as $i => $c)
                                    <tr wire:key="cart-{{ $i }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ ucfirst($c['name']) }}</td>
                                        <td>{{ $c['rekening'] }}</td>
                                        <td>Rp {{ number_format($c['saldo'], 0, ',', '.') }}</td>
                                        <td>
                                            <div x-data="{
                                                value: $wire.entangle('selectedNasabah.{{ $i }}.jumlah'),
                                                formatted: '',
                                                format(v) {
                                                    this.formatted = v ? 'Rp ' + Number(v).toLocaleString('id-ID') : '';
                                                },
                                                parse(v) {
                                                    this.value = v.replace(/[^0-9]/g, '');
                                                }
                                            }" x-init="format(value)">
                                                <input type="text"
                                                    class="form-control form-control-sm @error("selectedNasabah.{$i}.jumlah") is-invalid @enderror"
                                                    x-model="formatted"
                                                    @input="parse($event.target.value); format(value)"
                                                    @blur="format(value)" placeholder="Rp 0" />
                                                @error("selectedNasabah.{$i}.jumlah")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="removeCart({{ $i }})"
                                                class="btn btn-sm btn-link text-danger p-0">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align:center;padding:28px 10px;color:var(--muted);font-size:11px">
                                            <i class="bi bi-inbox"
                                                style="font-size:20px;display:block;margin-bottom:6px"></i>
                                            Belum ada item ditambahkan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button wire:click="simpanPenarikan" wire:loading.attr="disabled" class="w-btn w-btn-primary"
                            style="width:auto;padding:7px 16px">
                            <span wire:loading.remove wire:target="simpanPenarikan">
                                <i class="bi bi-check2-circle me-1"></i> Simpan Setoran
                            </span>
                            <span wire:loading wire:target="simpanPenarikan">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="wm-pilih-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Pilih Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="px-3 py-2 border-bottom">
                    <input type="text" wire:model.live="searchNasabah" class="form-control form-control-sm"
                        placeholder="Cari nama atau unit..." />
                </div>
                <div wire:loading.flex wire:target="getNasabah" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div wire:loading.flex wire:target="getNasabah" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div class="w-modal-body" style="overflow-y: auto; max-height: 60vh;">
                    <div class="d-flex flex-column gap-2">
                        @forelse ($this->nasabah as $n)
                            <div class="w-row" wire:click="pilihNasabah({{ $n->id }})" style="cursor:pointer"
                                wire:key="nasabah-{{ $n->id }}">
                                <div class="avatar" style="width:28px;height:28px;font-size:10px">
                                    {{ strtoupper($n->initials()) }}
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="w-row-title">{{ ucfirst($n->name) }}</div>
                                    <div class="w-row-meta">
                                        @foreach ($n->bukutabungans as $bk)
                                            {{ $bk->nomor_rekening }} - Rp
                                            {{ number_format($bk['saldo'], 0, ',', '.') }}
                                        @endforeach
                                    </div>
                                </div>
                                <span class="{{ $n->status == 'active' ? 'bs bs-green' : 'bs bs-warn' }}">
                                    {{ ucfirst($n->status) }}
                                </span>
                            </div>
                        @empty
                            <div
                                style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                                Belum ada data
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
