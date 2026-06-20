<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
    <div id="m-beranda">
        <div class="m-header">
            <div class="m-topbar">
                <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                    <div class="avatar avatar-md">{{ Auth::user()->initials()  }}</div>
                    <div>
                        <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat datang</div>
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
                        <span class="m-pill-n">{{ convertRupiahToString($data['totalSaldoSetoran']['today']) }}</span>
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

                        <span class="m-pill-l{{ $textClass }}" style="display:inline-flex; align-items:center; gap:1px;">
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
                        <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span class="svc-lbl">Saldo</span>
                    </a></div>
            </div>
        </div>
        @include('panel.template.mobile-bottombar')
    </div>
</div>
