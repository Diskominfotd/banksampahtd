<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-setoran" class="w-content">
                <div style="display:grid;grid-template-columns:300px 1fr;gap:16px;align-items:start">

                    {{-- Kiri: Nasabah --}}
                    <div class="w-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div style="font-size:12px;font-weight:700">Nasabah</div>
                                <div style="font-size:10px;color:var(--muted)">Pilih nasabah penyetor</div>
                            </div>
                            <button wire:click="getNasabah" data-bs-toggle="modal" data-bs-target="#wm-pilih-nasabah"
                                class="w-btn w-btn-primary" style="font-size:11px;width:auto;padding:6px 12px">
                                <i class="bi bi-person-check me-1"></i> Pilih
                            </button>
                        </div>
                        @if ($selectedNasabah)
                            <div class="w-row">
                                <div class="w-row-ico ic1">
                                    <i class="bi bi-recycle" style="font-size:13px"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="w-row-title">{{ ucfirst($selectedNasabah->name) }}</div>
                                    <div class="w-row-meta">{{ ucfirst($selectedNasabah->unit->nama) }}</div>
                                </div>
                                <span class="{{ $selectedNasabah->status == 'active' ? 'bs bs-green' : 'bs bs-warn' }}">
                                    {{ ucfirst($selectedNasabah->status) }}
                                </span>
                            </div>
                        @else
                            <div
                                style="padding:24px 16px;text-align:center;background:var(--bg-dark);border-radius:10px;border:1.5px dashed var(--border)">
                                <i class="bi bi-person-dash" style="font-size:22px;color:var(--muted)"></i>
                                <div style="font-size:11px;color:var(--muted);margin-top:6px">Belum ada nasabah dipilih
                                </div>
                            </div>
                        @endif
                        @error('nasabah')
                            <div class="mt-2" style="font-size:11px;color:#e74c3c">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Kanan: Item + Aksi --}}
                    <div class="d-flex flex-column gap-3">
                        <div class="w-panel">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div style="font-size:12px;font-weight:700">Item Setoran</div>
                                    <div style="font-size:10px;color:var(--muted)">Tambahkan jenis sampah yang disetor
                                    </div>
                                </div>
                                <button wire:click="getJenisSampah" data-bs-toggle="modal"
                                    data-bs-target="#wm-pilih-jenis" class="w-btn w-btn-primary"
                                    class="w-btn w-btn-primary" style="font-size:11px;width:auto;padding:6px 12px">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <table class="w-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Sampah</th>
                                        <th>Berat</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cart as $i => $c)
                                        <tr wire:key="cart-{{ $i }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $c['nama'] }}</td>
                                            <td>
                                                <input type="number" min="0.1" step="0.1"
                                                    wire:blur="updateBerat({{ $i }}, $event.target.value)"
                                                    value="{{ $c['berat'] ?: '' }}" placeholder="0.0"
                                                    class="form-control form-control-sm {{ $errors->has('cart_' . $i) ? 'is-invalid' : '' }}"
                                                    style="width:80px"
                                                    oninput="if(this.value.length > 1 && this.value[0] === '0' && this.value[1] !== '.') this.value = this.value.replace(/^0+/, '')">
                                                @error("cart_{$i}")
                                                    <div class="invalid-feedback" style="font-size:10px">
                                                        {{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>Rp {{ number_format($c['harga'], 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($c['harga'] * $c['berat'], 0, ',', '.') }}</td>
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
                                    @if (count($cart) > 0)
                                        <tr style="font-weight:700;font-size:12px">
                                            <td colspan="4" style="text-align:right">Total</td>
                                            <td>Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">
                            <button class="w-btn w-btn-ghost">Batal</button>
                            <button wire:click="simpanSetoran" wire:loading.attr="disabled" class="w-btn w-btn-primary"
                                style="width:auto;padding:7px 16px">

                                <span wire:loading.remove wire:target="simpanSetoran">
                                    <i class="bi bi-check2-circle me-1"></i> Simpan Setoran
                                </span>

                                <span wire:loading wire:target="simpanSetoran">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
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
                        placeholder="Cari nama atau rekening nasabah..." />
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
                                            {{ $bk->nomor_rekening }}
                                        @endforeach
                                    </div>
                                </div>
                                <span class="{{ $n->status == 'active' ? 'bs bs-green' : 'bs bs-warn' }}">
                                    {{ ucfirst($n->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="item-empty">
                                <i class="bi bi-inbox"></i>
                                Tidak Ada Data
                            </div>
                        @endforelse
                    </div>
                    @if (count($this->nasabah) >= 10)
                        <button type="button" wire:click="loadMoreNasabah"
                            style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                            <span wire:loading.remove wire:target="loadMoreNasabah">Tampilkan lebih banyak</span>
                            <span wire:loading wire:target="loadMoreNasabah">
                                <span class="spinner-border spinner-border-sm"
                                    style="width:12px;height:12px;border-width:1.5px;"></span>
                            </span>
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-pilih-jenis" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Pilih Jenis Sampah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="px-3 py-2 border-bottom">
                    <input type="text" wire:model.live="searchJenis" class="form-control form-control-sm"
                        placeholder="Cari nama atau rekening nasabah..." />
                </div>
                <div class="w-modal-body" style="overflow-y: auto; max-height: 60vh;">
                    <div class="d-flex flex-column gap-2">
                        @forelse ($this->items as $item)
                            <div class="w-row" wire:click="pilihJenisSampah({{ $item->id }})">
                                <div class="w-row-ico ic1"><i class="bi bi-recycle" style="font-size:13px"></i></div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="w-row-title">{{ $item->trash->nama }} - Rp
                                        {{ number_format($item->harga ?? 0, 0, ',', '.') }}/KG
                                    </div>
                                    <div class="w-row-meta">Tipe Harga - {{ $item->type }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="item-empty">
                                <i class="bi bi-inbox"></i>
                                Tidak Ada Data
                            </div>
                        @endforelse
                    </div>
                    @if (count($this->items) >= 10)
                        <button type="button" wire:click="loadMoreItemSampah"
                            style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                            <span wire:loading.remove wire:target="loadMoreItemSampah">Tampilkan lebih banyak</span>
                            <span wire:loading wire:target="loadMoreItemSampah">
                                <span class="spinner-border spinner-border-sm"
                                    style="width:12px;height:12px;border-width:1.5px;"></span>
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
