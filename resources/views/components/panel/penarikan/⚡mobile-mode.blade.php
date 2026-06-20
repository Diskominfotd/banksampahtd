<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('penarikan.saldo')"><i class="bi bi-chevron-left"
                    style="font-size:12px"></i>
            </div>
            <div class="ph-title">Buat Penarikan</div>
        </div>
        <div class="m-body">
            <div class="section-card mt-5">
                <div class="sec-head">
                    <div>
                        <div class="sec-title">Penarikan Saldo</div>
                        <div class="sec-sub">Pilih nasabah</div>
                    </div>
                    <button wire:click="getNasabah" @click="$store.sheet.show('pilih-nasabah')" class="btn-sm-green">
                        <i class="bi bi-person-check"></i> Pilih Nasabah
                    </button>
                </div>
                @forelse ($selectedNasabah as $i => $c)
                    <div class="item-row" wire:key="cart-{{ $i }}">
                        <div class="item-dot"></div>
                        <div style="flex:1;min-width:0">
                            <div class="item-nama">{{ ucfirst($c['name']) }}</div>
                            <div class="item-price">Rp {{ number_format($c['saldo'], 0, ',', '.') }}</div>
                        </div>
                        <div class="item-berat-wrap" style="width:120px">
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
                                    x-model="formatted" @input="parse($event.target.value); format(value)"
                                    @blur="format(value)" placeholder="Rp 0" />
                                @error("selectedNasabah.{$i}.jumlah")
                                    <div class="err-msg">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
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
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn-primary w-100" wire:loading.attr="disabled"
                    wire:click="simpanPenarikan">
                    <span wire:loading.remove wire:target="simpanPenarikan">
                        <i class="bi bi-check-circle me-1"></i>Simpan
                    </span>
                    <span wire:loading wire:target="simpanPenarikan">Loading...</span>
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
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Pilih Nasabah
            </div>
            <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="searchNasabah" placeholder="Cari nama jenis sampah...">
            </div>
        </div>
        <div wire:loading.flex wire:target="getNasabah" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 15px 15px;-webkit-overflow-scrolling:touch">
            @foreach ($this->nasabah as $n)
                <div class="list-item fade-up mb-1"wire:click="pilihNasabah({{ $n->id }})"
                    style="cursor:pointer">
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
                                {{ $bk->nomor_rekening }} - Rp
                                {{ number_format($bk['saldo'], 0, ',', '.') }}
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
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
