<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
    @if (Auth::user()->hasRole(['admin', 'supervisor']))
        {{-- AdminSupervisor --}}
        <div id="m-beranda">
            <div class="m-header">
                <div class="m-topbar">
                    <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                        <div class="avatar avatar-md">{{ Auth::user()->initials() }}</div>
                        <div>
                            <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat datang
                            </div>
                            <div style="font-size:14px;font-weight:600;color:#fff">{{ Auth::user()->unit->nama }}</div>
                        </div>
                    </div>
                    <div class="m-gear" onclick="mNav('m-notifikasi')"><i class="bi bi-bell-fill"></i><span
                            style="position:absolute;top:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:var(--red);border:2px solid rgba(255,255,255,.4);font-size:7px;display:flex;align-items:center;justify-content:center;font-weight:700">3</span>
                    </div>
                </div>
                <div class="m-summary fade-up">
                    <div class="m-summary-lbl">Total Berat Sampah</div>
                    <div class="m-summary-num">
                        {{ number_format($data['totalBeratSetoran']['today'], 0, ',', '.') }} Kg

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
                                default => '',
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
                        <div class="m-pill c">
                            <span class="m-pill-n">{{ $data['totalNasabah']['today'] }}</span>
                            <span class="m-pill-l">Nasabah</span>

                            @php
                                $diff = $data['totalNasabah']['difference'];
                                $textClass = $diff < 0 ? 'text-danger' : '';
                            @endphp

                            <span class="m-pill-l{{ $textClass }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $diff >= 0 ? 'up' : 'down' }}-short"></i>
                                @if ($diff == 0)
                                    Sama
                                @else
                                    {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                @endif
                            </span>
                        </div>
                        <div class="m-pill c">
                            <span
                                class="m-pill-n">{{ convertRupiahToString($data['totalSaldoSetoran']['today']) }}</span>
                            <span class="m-pill-l">Setoran</span>

                            @php
                                $persentase = $data['totalSaldoSetoran']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                                $textClass = $arah === 'down' ? 'text-danger' : '';
                            @endphp

                            <span class="m-pill-l{{ $textClass }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                @if ($arah === 'neutral')
                                    Sama
                                @else
                                    {{ $arah === 'up' ? '+' : '' }}{{ $persentase }}%
                                @endif
                            </span>
                        </div>
                        <div class="m-pill">
                            <span
                                class="m-pill-n">{{ convertRupiahToString($data['totalPenarikanSaldoNasabah']['today']) }}</span>
                            <span class="m-pill-l">Tarik</span>

                            @php
                                $persentase = $data['totalPenarikanSaldoNasabah']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                                $textClass = $arah === 'down' ? 'text-danger' : '';
                            @endphp

                            <span class="m-pill-l{{ $textClass }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                @if ($arah === 'neutral')
                                    Sama
                                @else
                                    {{ $arah === 'up' ? '+' : '' }}{{ $persentase }}%
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-body">
                <div class="sec-lbl">Menu Utama</div>
                <div class="row g-2">
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('setoran') }}">
                            <div class="svc-icon ic1"><i class="bi bi-recycle"></i>
                                <div class="notif-dot">5</div>
                            </div><span class="svc-lbl">Setoran</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('nasabah') }}">
                            <div class="svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                                class="svc-lbl">Nasabah</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                            <div class="svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                                class="svc-lbl">Harga</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('kategori') }}">
                            <div class="svc-icon ic4"><i class="bi bi-grid-fill"></i></div><span
                                class="svc-lbl">Kategori</span>
                        </a></div>
                </div>
                <div class="row g-2 mt-1">
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('penarikan.saldo') }}">
                            <div class="svc-icon ic5"><i class="bi bi-cash-coin"></i></div><span
                                class="svc-lbl">Penarikan</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                            <div class="svc-icon ic3"><i class="bi bi-graph-up"></i></div><span
                                class="svc-lbl">Laporan</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-setoran')">
                            <div class="svc-icon ic6"><i class="bi bi-person-circle"></i></div><span
                                class="svc-lbl">Profile</span>
                        </a></div>
                    <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-penarikan')">
                            <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span
                                class="svc-lbl">Saldo</span>
                        </a></div>
                </div>
            </div>
            @include('panel.template.mobile-bottombar')
        </div>
    @else
        {{-- Nasabah --}}
        <div id="m-beranda">
            <div class="m-header">
                <div class="m-topbar">
                    <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                        <div class="avatar avatar-md">{{ Auth::user()->initials() }}</div>
                        <div>
                            <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat datang
                            </div>
                            <div style="font-size:14px;font-weight:600;color:#fff">{{ Auth::user()->unit->nama }}</div>
                        </div>
                    </div>
                    <div class="m-gear" onclick="mNav('m-notifikasi')"><i class="bi bi-bell-fill"></i><span
                            style="position:absolute;top:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:var(--red);border:2px solid rgba(255,255,255,.4);font-size:7px;display:flex;align-items:center;justify-content:center;font-weight:700">3</span>
                    </div>
                </div>
                <div class="m-summary fade-up">
                    <div class="m-summary-lbl">Total Saldo Anda</div>
                    <div class="m-summary-num">
                        Rp {{ convertRupiahToString($data['totalSaldoNasabah']['today']) }}
                        @php
                            $persentase = $data['totalSaldoNasabah']['persentase'];
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
                                default => '',
                            };
                        @endphp

                        <i class="bi {{ $iconClass }}" style="font-size: 0.6em;"></i>
                        <span class="m-summary-percent {{ $textClass }}" style="font-size: 0.5em;">
                            @if ($arah === 'neutral')
                                Sama
                            @else
                                {{ number_format(abs($persentase), 1, ',', '.') }}%
                            @endif
                        </span>
                    </div>
                    <div class="m-pills">
                        <div class="m-pill c" wire:click="getBukuTabungan"
                            @click="$store.sheet.show('detail-saldo')">
                            <span class="m-pill-n">{{ $data['totalRekeningNasabah'] }}</span>
                            <span class="m-pill-l">Rekening</span>
                        </div>
                        <div class="m-pill c">
                            <span
                                class="m-pill-n">{{ convertRupiahToString($data['totalSetoranNasabah']['today']) }}</span>
                            <span class="m-pill-l">Setoran</span>
                            @php
                                $persentase = $data['totalSetoranNasabah']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                                $textClass = $arah === 'down' ? 'text-danger' : '';
                            @endphp

                            <span class="m-pill-l{{ $textClass }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                @if ($arah === 'neutral')
                                    Sama
                                @else
                                    {{ $arah === 'up' ? '+' : '' }}{{ $persentase }}%
                                @endif
                            </span>
                        </div>
                        <div class="m-pill">
                            <span
                                class="m-pill-n">{{ convertRupiahToString($data['totalPenarikanNasabah']['today']) }}</span>
                            <span class="m-pill-l">Tarik</span>

                            @php
                                $persentase = $data['totalPenarikanNasabah']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                                $textClass = $arah === 'down' ? 'text-danger' : '';
                            @endphp

                            <span class="m-pill-l{{ $textClass }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                @if ($arah === 'neutral')
                                    Sama
                                @else
                                    {{ $arah === 'up' ? '+' : '' }}{{ $persentase }}%
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-body">
                <div class="sec-lbl">Menu Utama</div>
                <div class="row g-2">
                    <div class="col-3 fade-up"><a class="svc-item" wire:click="getBukuTabungan"
                            @click="$store.sheet.show('rekening-nasabah')">
                            <div class="svc-icon ic1"><i class="bi bi-bank"></i>
                                <div class="notif-dot">{{ $data['totalRekeningNasabah'] }}</div>
                            </div><span class="svc-lbl">Buka Rekening</span>
                        </a>
                    </div>
                    <div class="col-3 fade-up"><a class="svc-item" wire:click="getBukuTabungan"
                            @click="$store.sheet.show('detail-saldo')">
                            <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span
                                class="svc-lbl">Saldo</span>
                        </a>
                    </div>
                    <div class="col-3 fade-up"><a class="svc-item" wire:click="getSetoranByAuthUser"
                            @click="$store.sheet.show('detail-setoran')">
                            <div class="svc-icon ic2"><i class="bi bi-recycle"></i></div><span
                                class="svc-lbl">Setoran</span>
                        </a>
                    </div>
                    <div class="col-3 fade-up"><a class="svc-item" wire:click="getTrxByAuthUser"
                            @click="$store.sheet.show('detail-trx')">
                            <div class="svc-icon ic2"><i class="bi bi-cash-stack"></i></div><span
                                class="svc-lbl">Penarikan</span>
                        </a>
                    </div>
                </div>
                <div class="sec-lbl">Setoran Terbaru</div>
                <div class="row g-2">
                    @if ($data['setoranNasabahByLimit']->isNotEmpty())
                        @foreach ($data['setoranNasabahByLimit'] as $snbl)
                            <div class="list-item fade-up"><span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                                <div class="list-main">
                                    <div class="list-name">{{ $snbl->created_at->format('d M Y') }} —
                                        {{ number_format($snbl->total_berat, 0, ',', '.') }} Kg
                                    </div>
                                    <div class="list-sub">{{ $snbl->bukutabungan->bank->nama }} ·
                                        {{ $snbl->created_at->diffForHumans() }}
                                    </div>
                                </div><span class="bs bs-green" style="cursor:pointer"
                                    onclick="openDetail('m-detail-setoran')">
                                    Rp
                                    {{ number_format($snbl->total_saldo, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada data
                        </div>
                    @endif
                </div>
                <div class="sec-lbl">Penarikan Terbaru</div>
                <div class="row g-2">
                    @if ($data['trxNasabahByLimit']->isNotEmpty())
                        @foreach ($data['trxNasabahByLimit'] as $trxbl)
                            <div class="list-item fade-up"><span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic2"><i class="bi bi-cash-coin" style="font-size:12px"></i>
                                </div>
                                <div class="list-main">
                                    <div class="list-name">{{ $trxbl->created_at->format('d M Y') }} —
                                        {{ $trxbl->bukutabungan->nomor_rekening }}</div>
                                    <div class="list-sub">{{ $trxbl->bukutabungan->bank->nama }} ·
                                        {{ $trxbl->created_at->diffForHumans() }} · Rp
                                        <b> Sisa - {{ number_format($trxbl->sisa_saldo, 0, ',', '.') }}</b>
                                    </div>
                                </div><span class="bs bs-green" style="cursor:pointer">
                                    Rp {{ number_format($trxbl->total_penarikan, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada data
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- Backdrop Rekening --}}
        <div x-show="$store.sheet.is('rekening-nasabah')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Rekening --}}
        <div x-show="$store.sheet.is('rekening-nasabah')" x-transition:enter="transition ease-out duration-300"
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
                Rekening Nasabah
            </div>
            <div wire:loading.flex wire:target="getBukuTabungan" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>

            <form wire:submit="addBukuTabungan">
                <div class="f-group">
                    <label>Unit</label>
                    <select class="f-input" wire:model="unitBukuTabungan">
                        <option value="">Pilih Unit</option>
                        @foreach ($data['banksampah'] as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                        @endforeach
                    </select>
                    @error('unitBukuTabungan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn-primary w-100" style="width:100%" wire:loading.attr="disabled"
                        wire:target="addBukuTabungan">
                        <span wire:loading.remove wire:target="addBukuTabungan">
                            <i class="bi bi-check-lg me-1"></i>Tambahkan
                        </span>
                        <span wire:loading wire:target="addBukuTabungan">Loading...</span>
                    </button>
                </div>
            </form>
            <div style="margin-top:20px">
                <div style="font-size:13px;font-weight:600;color:var(--text-main);margin-bottom:10px">
                    Daftar Buku Tabungan
                </div>
                @if (!empty($bukuTabungan))
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach ($bukuTabungan as $bk)
                            <div
                                style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;border-radius:12px;
                                background:var(--bg-body,#f9fafb);
                                border:1px solid var(--border-color,#e5e7eb)">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div
                                        style="width:34px;height:34px;border-radius:50%;
                                        background:#dcfce7;display:flex;align-items:center;
                                        justify-content:center">
                                        <i class="bi bi-bank" style="font-size:14px;color:#16a34a"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:var(--text-main)">
                                            {{ $bk['bank']['nama'] }}
                                        </div>
                                        <div
                                            style="font-size:11px;color:var(--text-muted,#6b7280);font-family:monospace">
                                            {{ $bk['nomor_rekening'] }}
                                        </div>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right"
                                    style="font-size:13px;color:var(--text-muted,#9ca3af)"></i>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                        <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                        Belum ada buku tabungan
                    </div>
                @endif
            </div>

        </div>

        {{-- Detail Saldo --}}
        <div x-show="$store.sheet.is('detail-saldo')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail Saldo --}}
        <div x-show="$store.sheet.is('detail-saldo')" x-transition:enter="transition ease-out duration-300"
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
            </div>
            <div wire:loading.flex wire:target="getBukuTabungan" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            <div style="margin-top:20px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <div style="font-size:13px;font-weight:600;color:var(--text-main)">
                        Daftar Buku Tabungan
                    </div>
                    <div style="font-size:13px;font-weight:700;color:#16a34a">
                        Rp {{ number_format(array_sum(array_column($bukuTabungan, 'saldo')), 0, ',', '.') }}
                    </div>
                </div>
                @if (!empty($bukuTabungan))
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach ($bukuTabungan as $bk)
                            <div
                                style="display:flex;justify-content:space-between;align-items:center;
                                padding:10px 14px;border-radius:12px;
                                background:var(--bg-body,#f9fafb);
                                border:1px solid var(--border-color,#e5e7eb)">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div
                                        style="width:34px;height:34px;border-radius:50%;
                                        background:#dcfce7;display:flex;align-items:center;
                                        justify-content:center">
                                        <i class="bi bi-bank" style="font-size:14px;color:#16a34a"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:var(--text-main)">
                                            {{ $bk['bank']['nama'] }}
                                        </div>
                                        <div
                                            style="font-size:11px;color:var(--text-muted,#6b7280);font-family:monospace">
                                            {{ $bk['nomor_rekening'] }} - Rp
                                            {{ number_format($bk['saldo'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <i class="bi bi-chevron-right"
                                    style="font-size:13px;color:var(--text-muted,#9ca3af)"></i>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                        <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                        Belum ada buku tabungan
                    </div>
                @endif
            </div>
        </div>

        {{-- Detail setoran --}}
        <div x-show="$store.sheet.is('detail-setoran')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail setoran --}}
        <div x-show="$store.sheet.is('detail-setoran')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Setoran
                </div>
                {{-- <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="searchSetoran" placeholder="Cari nama jenis sampah...">
            </div> --}}
            </div>
            <div style="flex:1;overflow-y:auto;padding:0 10px 10px;-webkit-overflow-scrolling:touch">
                <div wire:loading.flex wire:target="getSetoranByAuthUser"
                    class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div class="row g-2">
                    @if (!empty($setoranNasabah))
                        @foreach ($setoranNasabah as $stn)
                            <div class="list-item fade-up"><span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                                <div class="list-main">
                                    <div class="list-name">{{ $snbl->created_at->format('d M Y') }} —
                                        {{ number_format($stn->total_berat, 0, ',', '.') }} Kg
                                    </div>
                                    <div class="list-sub">{{ $stn->bukutabungan->bank->nama }} ·
                                        {{ $stn->created_at->diffForHumans() }}
                                    </div>
                                </div><span class="bs bs-green" style="cursor:pointer""> Rp
                                    {{ number_format($stn->total_saldo, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada data
                        </div>
                    @endif
                </div>
                @if (count($this->setoranNasabah) >= 10)
                    <button type="button" wire:click="loadMoreSetoran"
                        style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                        <span wire:loading.remove wire:target="loadMoreSetoran">Tampilkan lebih banyak</span>
                        <span wire:loading wire:target="loadMoreSetoran">
                            <span class="spinner-border spinner-border-sm"
                                style="width:12px;height:12px;border-width:1.5px;"></span>
                        </span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Detail transaksi --}}
        <div x-show="$store.sheet.is('detail-trx')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail transaksi --}}
        <div x-show="$store.sheet.is('detail-trx')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Penarikan
                </div>
                {{-- <div class="m-search mb-3">
                <i class="bi bi-search si"></i>
                <input type="text" wire:model.live="searchSetoran" placeholder="Cari nama jenis sampah...">
            </div> --}}
            </div>
            <div style="flex:1;overflow-y:auto;padding:0 10px 10px;-webkit-overflow-scrolling:touch">
                <div wire:loading.flex wire:target="getTrxByAuthUser"
                    class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <div class="row g-2">
                    @if (!empty($trxNasabah))
                        @foreach ($trxNasabah as $trx)
                            <div class="list-item fade-up"><span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic2"><i class="bi bi-cash-coin" style="font-size:12px"></i>
                                </div>
                                <div class="list-main">
                                    <div class="list-name">{{ $trx->created_at->format('d M Y') }} —
                                        {{ $trx->bukutabungan->nomor_rekening }}</div>
                                    <div class="list-sub">{{ $trx->bukutabungan->bank->nama }} ·
                                        {{ $trx->created_at->diffForHumans() }} · Rp
                                        <b> Sisa - {{ number_format($trx->sisa_saldo, 0, ',', '.') }}</b>
                                    </div>
                                </div><span class="bs bs-green" style="cursor:pointer">
                                    Rp {{ number_format($trx->total_penarikan, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada data
                        </div>
                    @endif
                </div>
                @if (count($this->trxNasabah) >= 10)
                    <button type="button" wire:click="loadMoreTrx"
                        style="width:100%;padding:8px;border:0.5px solid #e0e0e0;border-radius:10px;background:none;font-size:13px;color:#198754;margin-top:8px;">
                        <span wire:loading.remove wire:target="loadMoreTrx">Tampilkan lebih banyak</span>
                        <span wire:loading wire:target="loadMoreTrx">
                            <span class="spinner-border spinner-border-sm"
                                style="width:12px;height:12px;border-width:1.5px;"></span>
                        </span>
                    </button>
                @endif

            </div>
        </div>
    @endif
</div>
