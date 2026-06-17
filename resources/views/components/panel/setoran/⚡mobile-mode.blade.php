<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger --}}
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Buat Setoran</div>
        </div>
        <div class="m-body">
            <div class="section-card mt-5">
                <div class="sec-head">
                    <div>
                        <div class="sec-title">Nasabah</div>
                        <div class="sec-sub">Pilih nasabah penyetor</div>
                    </div>
                    <button wire:click="getNasabah" @click="$store.sheet.show('pilih-nasabah')" class="btn-sm-green">
                        <i class="bi bi-person-check"></i> Pilih
                    </button>
                </div>
                @if ($selectedNasabah)
                    <div class="nasabah-row">
                        <div class="avatar avatar-sm">
                            {{ strtoupper(substr($selectedNasabah->name, 0, 2)) }}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="nasabah-name">{{ ucfirst($selectedNasabah->name) }}</div>
                            <div class="nasabah-unit">{{ ucfirst($selectedNasabah->unit->nama) }}</div>
                        </div>
                        <span class="{{ $selectedNasabah->status === 'active' ? 'bs bs-ok' : 'bs bs-warn' }}">
                            {{ ucfirst($selectedNasabah->status) }}
                        </span>
                    </div>
                @else
                    <div class="nasabah-empty">
                        <i class="bi bi-person-dash"></i>
                        <p>Belum ada nasabah dipilih</p>
                    </div>
                @endif

                @error('nasabah')
                    <div class="err-msg">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
            {{-- Item Setoran --}}
            <div class="section-card">
                <div class="sec-head">
                    <div>
                        <div class="sec-title">Item Setoran</div>
                        <div class="sec-sub">Tambahkan jenis sampah yang disetor</div>
                    </div>
                    <button wire:click="getJenisSampah" @click="$store.sheet.show('pilih-jenis-sampah')"
                        class="btn-sm-green">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                </div>

                @forelse ($cart as $i => $c)
                    <div class="item-row" wire:key="cart-{{ $i }}">
                        <div class="item-dot"></div>
                        <div style="flex:1;min-width:0">
                            <div class="item-nama">{{ $c['nama'] }}</div>
                            <div class="item-price">Rp {{ number_format($c['harga'], 0, ',', '.') }}/kg</div>
                        </div>
                        <div class="item-berat-wrap">
                            <input type="number" min="0.1" step="0.1"
                                wire:blur="updateBerat({{ $i }}, $event.target.value)"
                                value="{{ $c['berat'] ?: '' }}" placeholder="0.0"
                                class="item-berat {{ $errors->has('cart_' . $i) ? 'border-danger' : '' }}">
                            <span class="item-berat-unit">kg</span>
                        </div>
                        <div class="item-subtotal">
                            Rp {{ number_format($c['harga'] * $c['berat'], 0, ',', '.') }}
                        </div>
                        <div class="item-del" wire:click="removeCart({{ $i }})">
                            <i class="bi bi-trash3"></i>
                        </div>
                    </div>
                @empty
                    <div class="item-empty">
                        <i class="bi bi-inbox"></i>
                        Belum ada item ditambahkan
                    </div>
                @endforelse

                @if (count($cart) > 0)
                    <div class="total-row">
                        <span class="total-label">Total Setoran</span>
                        <span class="total-val">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</span>
                    </div>
                @endif

                @error('cart')
                    <div class="err-msg">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:click="simpanSetoran">
                    <span wire:loading.remove wire:target="simpanSetoran">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="simpanSetoran">Loading...</span>
                </button>
            </div>
        </div>

    </div>

    <div x-show="$store.sheet.is('pilih-nasabah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>

    <div x-show="$store.sheet.is('pilih-nasabah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:999;background:var(--bg-card,#fff);border-radius:20px 20px 0 0;padding:20px;max-height:90dvh;overflow-y:auto"
        x-cloak>
        <div class="sheet-handle"></div>
        <div
            style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px;color:var(--text-main)">
            Pilih Nasabah
        </div>
        @foreach ($this->nasabah as $n)
            <div class="list-item fade-up mb-1"wire:click="pilihNasabah({{ $n->id }})" style="cursor:pointer">
                <div class="list-ico ic1">
                    <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)">
                        <div class="avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                            {{ strtoupper($n->initials()) }}
                        </div>
                    </div>
                </div>
                <div class="list-main">
                    <div class="list-name">{{ ucfirst($n->name) }}</div>
                    <div class="list-sub">
                        @foreach ($n->bukutabungans as $bk)
                            {{ $bk->nomor_rekening }}
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div x-show="$store.sheet.is('pilih-jenis-sampah')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>

    {{-- Sheet --}}
    <div x-show="$store.sheet.is('pilih-jenis-sampah')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>

        {{-- Header sticky --}}
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Pilih Jenis Sampah
            </div>
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="keyword" placeholder="Cari nama jenis sampah...">
            </div>
        </div>

        {{-- List scroll --}}
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            @foreach ($this->items as $item)
                <div class="list-item fade-up mb-1" wire:click="pilihJenisSampah({{ $item->id }})"
                    style="cursor:pointer">
                    <div class="list-ico ic1">
                        <div class="w-row-ico ic1"><i class="bi bi-recycle" style="font-size:13px"></i></div>
                    </div>
                    <div class="list-main">
                        <div class="list-name">{{ $item->trash->nama }} - Rp
                            {{ number_format($item->harga ?? 0, 0, ',', '.') }}/KG</div>
                        <div class="list-sub">
                            Tipe Harga - {{ $item->type }}
                        </div>
                    </div>
                </div>
            @endforeach
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
