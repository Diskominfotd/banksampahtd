<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Life is available only in the present moment. - Thich Nhat Hanh --}}
    <style>
        .tab-active {
            color: var(--cyan, #2e7d32);
            border-bottom: 2px solid var(--cyan, #2e7d32);
        }

        .tab-inactive {
            color: #9ca3af;
        }
    </style>
    <div id="m-nasabah">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')">
                <i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Gudang</div>
            <div class="ms-auto d-flex gap-2 mb-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    @click="$store.sheet.show('buat-trx')">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <div class="m-gear"
                    style="font-size:14px;background:var(--red-10);border:1px solid var(--border);color:var(--red)"
                    @click="$store.sheet.show('buat-trx-pengeluaran')">
                    <i class="bi bi-dash-circle"></i>
                </div>
            </div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="m-summary fade-up"
                style="background: linear-gradient(135deg, #0a4d68 0%, #088395 60%, #05bfdb 100%); border-radius: 12px; padding: 16px; color: #fff;">
                <div class="m-summary-lbl" style="color: rgba(255,255,255,0.8);">Total Berat Sampah</div>
                <div class="m-summary-num">
                    {{ number_format($data['totalStokGudang']['total'], 0, ',', '.') }} Kg

                    @php
                        $persentase = $data['totalBeratSetoran']['persentase'];
                        $arah = match (true) {
                            $persentase > 0 => 'up',
                            $persentase < 0 => 'down',
                            default => 'neutral',
                        };
                        $iconClass = match ($arah) {
                            'up' => 'bi-arrow-up-short',
                            'down' => 'bi-arrow-down-short text-danger',
                            default => 'bi-dash',
                        };
                        $textClass = match ($arah) {
                            'down' => 'text-danger',
                            default => 'text-white',
                        };
                    @endphp

                    <i class="bi {{ $iconClass }}" style="font-size: 0.6em;"></i>
                    <span class="m-summary-percent {{ $textClass }}" style="font-size: 0.6em;">
                        @if ($arah === 'neutral')
                            Sama
                        @else
                            {{ number_format(abs($persentase), 1, ',', '.') }}%
                        @endif
                    </span>
                </div>
                <div class="m-pills">
                    @php
                        $summaryArah = fn($p) => match (true) {
                            $p > 0 => 'up',
                            $p < 0 => 'down',
                            default => 'neutral',
                        };
                    @endphp
                    @php
                        $arah = $summaryArah($data['totalPendapatan']['persentase']);
                        $persen = $data['totalPendapatan']['persentase'];
                    @endphp
                    <div class="m-pill c">
                        <span class="m-pill-n">{{ convertRupiahToString($data['totalPendapatan']['total']) }}</span>
                        <span class="m-pill-l">Pendapatan</span>
                        <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                            style="display:inline-flex; align-items:center; gap:1px;">
                            <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                            {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
                        </span>
                    </div>
                    @php
                        $arah = $summaryArah($data['totalPenarikanSaldoNasabah']['persentase']);
                        $persen = $data['totalPenarikanSaldoNasabah']['persentase'];
                    @endphp
                    <div class="m-pill c">
                        <span
                            class="m-pill-n">{{ convertRupiahToString($data['totalPenarikanSaldoNasabah']['total']) }}</span>
                        <span class="m-pill-l">Pengeluaran</span>
                        <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                            style="display:inline-flex; align-items:center; gap:1px;">
                            <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                            {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
                        </span>
                    </div>
                </div>
            </div>
            <div x-data="{ tab: 'trx' }">

                {{-- ===== TAB SWITCHER ===== --}}
                <div class="d-flex gap-2 mb-3" style="border-bottom:1px solid #eee;">
                    <button type="button" @click="tab = 'trx'"
                        :class="tab === 'trx' ? 'tab-active' : 'tab-inactive'"
                        style="flex:1;padding:8px 0;border:0;background:none;font-size:13px;font-weight:600;">
                        Transaksi Gudang
                    </button>
                    <button type="button" @click="tab = 'pengeluaran'"
                        :class="tab === 'pengeluaran' ? 'tab-active' : 'tab-inactive'"
                        style="flex:1;padding:8px 0;border:0;background:none;font-size:13px;font-weight:600;">
                        Pengeluaran
                    </button>
                </div>

                {{-- ===== TAB: TRANSAKSI GUDANG ===== --}}
                <div x-show="tab === 'trx'">
                    <div class="row g-2">
                        @forelse ($data['trx'] ?? [] as $trx)
                            <div class="tx-card d-flex align-items-start gap-2 fade-up"
                                wire:click="trxDetail('{{ encrypt($trx->id) }}')"
                                @click="$store.sheet.show('detail-trx')">
                                <div class="tx-ico" style="background:rgba(46,125,50,.10);color:#2e7d32">
                                    <i class="bi bi-box-fill" style="font-size:14px"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="tx-name text-truncate">{{ ucfirst($trx->kode) }} —
                                        {{ number_format($trx->total_berat, 0, ',', '.') }} Kg</div>
                                    <div class="tx-date">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $trx->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                        {{ $trx->admin->name ?? '-' }}
                                    </div>
                                </div>
                                <span class="bs bs-green flex-shrink-0">
                                    Rp {{ number_format($trx->total_penarikan, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div
                                style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                                Belum ada Data
                            </div>
                        @endforelse
                    </div>
                    @if ($data['trx']->count() >= $pageTrx)
                        <button type="button" wire:click="loadPerpageTrx"
                            style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                            <span wire:loading.remove wire:target="loadPerpageTrx">Tampilkan lebih banyak</span>
                            <span wire:loading wire:target="loadPerpageTrx">
                                <span class="spinner-border spinner-border-sm"
                                    style="width:12px;height:12px;border-width:1.5px;"></span>
                            </span>
                        </button>
                    @endif
                </div>

                {{-- ===== TAB: PENGELUARAN ===== --}}
                <div x-show="tab === 'pengeluaran'" x-cloak>
                    <div class="row g-2">
                        @forelse ($data['pengeluaran'] ?? [] as $pg)
                            <div class="tx-card d-flex align-items-start gap-2 fade-up"
                                wire:click="trxDetailPengeluaran('{{ encrypt($pg->id) }}')"
                                @click="$store.sheet.show('detail-trx-pengeluaran')">
                                <div class="tx-ico" style="background:rgba(220,53,69,.10);color:#dc3545">
                                    <i class="bi bi-dash-circle" style="font-size:14px"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="tx-name text-truncate">{{ $pg->kode ?? '-' }}</div>
                                    <div class="tx-date">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $pg->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                        {{ $pg->admin->name ?? '-' }}
                                    </div>
                                </div>
                                <span class="bs bs-err flex-shrink-0">
                                    - Rp {{ number_format($pg->total_penarikan, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <div
                                style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                                Belum ada Data
                            </div>
                        @endforelse
                    </div>
                    @if ($data['pengeluaran']->count() >= $pagePgn)
                        <button type="button" wire:click="loadPerpagePengeluaran"
                            style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                            <span wire:loading.remove wire:target="loadPerpagePengeluaran">Tampilkan lebih banyak</span>
                            <span wire:loading wire:target="loadPerpagePengeluaran">
                                <span class="spinner-border spinner-border-sm"
                                    style="width:12px;height:12px;border-width:1.5px;"></span>
                            </span>
                        </button>
                    @endif
                </div>

            </div>

        </div>
    </div>
    <div x-show="$store.sheet.is('buat-trx')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('buat-trx')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Buat Transaksi Pendapatan
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="doTrxGudang">
                <div class="f-group" x-data="{
                    display: '',
                    init() {
                        this.display = this.format(this.$wire.totalNilai || 0);
                    },
                    format(val) {
                        let num = val.toString().replace(/\D/g, '');
                        if (!num) num = '0';
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                    },
                    update(e) {
                        let raw = e.target.value.replace(/\D/g, '');
                        if (!raw) raw = '0';
                        this.$wire.totalNilai = raw;
                        this.display = this.format(raw);
                    }
                }">
                    <label>Nilai Rupiah</label>
                    <input class="f-input" type="text" :value="display" @input="update($event)"
                        placeholder="ex: Rp 50.000">
                    @error('totalNilai')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <label>Keterangan</label>
                    <textarea class="f-input" wire:model="keterangan" rows="3" cols="3"
                        placeholder="ex: Bongkar gudang untuk keperluan ...">
                    </textarea>
                    @error('keterangan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                        wire:target="doTrxGudang">
                        <span wire:loading.remove wire:target="doTrxGudang">
                            <i class="bi bi-check-lg me-1"></i>Buat Transaksi
                        </span>
                        <span wire:loading wire:target="doTrxGudang">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
     <div x-show="$store.sheet.is('buat-trx-pengeluaran')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('buat-trx-pengeluaran')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Buat Transaksi Pengeluaran
            </div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            <form wire:submit="addPengeluaran">
                <div class="f-group" x-data="{
                    display: '',
                    init() {
                        this.display = this.format(this.$wire.totalNilaiPengeluaran || 0);
                    },
                    format(val) {
                        let num = val.toString().replace(/\D/g, '');
                        if (!num) num = '0';
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                    },
                    update(e) {
                        let raw = e.target.value.replace(/\D/g, '');
                        if (!raw) raw = '0';
                        this.$wire.totalNilaiPengeluaran = raw;
                        this.display = this.format(raw);
                    }
                }">
                    <label>Nilai Rupiah</label>
                    <input class="f-input" type="text" :value="display" @input="update($event)"
                        placeholder="ex: Rp 50.000">
                    @error('totalNilaiPengeluaran')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <label>Keterangan</label>
                    <textarea class="f-input" wire:model="keteranganPengeluaran" rows="3" cols="3"
                        placeholder="ex: Bayar rambahan dan PLN....">
                    </textarea>
                    @error('keteranganPengeluaran')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                        wire:target="addPengeluaran">
                        <span wire:loading.remove wire:target="addPengeluaran">
                            <i class="bi bi-check-lg me-1"></i>Buat Transaksi
                        </span>
                        <span wire:loading wire:target="addPengeluaran">Loading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>



    <div x-show="$store.sheet.is('detail-trx-pengeluaran')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('detail-trx-pengeluaran')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Detail Transaksi Pengeluaran - {{ $itemTrxPengeluaran->kode ?? 'PRN-XXX-XXX-XXX' }}
            </div>
        </div>
        <div wire:loading.flex wire:target="trxDetailPengeluaran" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            @if ($itemTrxPengeluaran)
                <div
                    style="background:var(--red-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                    <div
                        style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                        Nilai Penarikan</div>
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--red)">Rp
                        {{ number_format($itemTrxPengeluaran->total_penarikan, 0, ',', '.') ?? 0 }}
                    </div>
                    <div class="detail-field"><span class="df-key">Petugas</span><span class="df-val">
                            {{ ucfirst($itemTrxPengeluaran->admin->name) }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Keterangan</span>
                        <span class="df-val">
                            {{ $itemTrxPengeluaran->keterangan ?? '-' }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                            {{ $itemTrxPengeluaran->created_at->format('Y-m-d') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div x-show="$store.sheet.is('detail-trx')" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
    </div>
    <div x-show="$store.sheet.is('detail-trx')" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
        <div style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
            <div class="sheet-handle"></div>
            <div
                style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                Detail Transaksi Pendapatan - {{ $itemTrx->kode ?? 'TRX-XXX-XXX-XXX' }}
            </div>
        </div>
        <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
            <div class="spinner-border text-success"></div>
        </div>
        <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
            @if ($itemTrx)
                <div
                    style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                    <div
                        style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                        Nilai Penarikan</div>
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">Rp
                        {{ number_format($itemTrx->total_penarikan, 0, ',', '.') ?? 0 }}
                    </div>
                    <div class="detail-field"><span class="df-key">Petugas</span><span class="df-val">
                            {{ ucfirst($itemTrx->admin->name) }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Keterangan</span>
                        <span class="df-val">
                            {{ $itemTrx->keterangan ?? '-' }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                            {{ $itemTrx->created_at->format('Y-m-d') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>


</div>
