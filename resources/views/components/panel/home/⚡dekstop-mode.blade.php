<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-dashboard" class="w-content">
                <div class="row g-3">
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Total Sampah Hari Ini</div>
                            <div class="w-m-val" style="color:var(--cyan)">
                                {{ number_format($data['totalBeratSetoran']['today'], 0, ',', '.') }} kg
                            </div>
                            @php
                                $persentase = $data['totalBeratSetoran']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                            @endphp

                            <div class="w-m-delta {{ $arah }}">
                                @if ($arah === 'up')
                                    <i class="bi bi-arrow-up-short"></i>+{{ $persentase }}% kemarin
                                @elseif ($arah === 'down')
                                    <i class="bi bi-arrow-down-short"></i>{{ $persentase }}% kemarin
                                @else
                                    <i class="bi bi-dash"></i> Sama seperti kemarin
                                @endif
                            </div>
                            @php
                                $persen = $data['totalBeratSetoran']['persentase'];
                                $barColor = match (true) {
                                    $persen < 50 => 'var(--red)',
                                    $persen < 75 => 'var(--orange)',
                                    default => 'var(--green)',
                                };
                            @endphp
                            <div class="w-bar">
                                <div class="w-bar-fill"
                                    style="width:{{ $persen }}%; background: {{ $barColor }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nilai Setoran</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp
                                {{ convertRupiahToString($data['totalSaldoSetoran']['today']) }}
                            </div>
                            @php
                                $persentase = $data['totalBeratSetoran']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                            @endphp

                            <div class="w-m-delta {{ $arah }}">
                                @if ($arah === 'up')
                                    <i class="bi bi-arrow-up-short"></i>+{{ $persentase }}% kemarin
                                @elseif ($arah === 'down')
                                    <i class="bi bi-arrow-down-short"></i>{{ $persentase }}% kemarin
                                @else
                                    <i class="bi bi-dash"></i> Sama seperti kemarin
                                @endif
                            </div>
                            @php
                                $persen = $data['totalBeratSetoran']['persentase'];
                                $barColor = match (true) {
                                    $persen < 50 => 'var(--red)',
                                    $persen < 75 => 'var(--orange)',
                                    default => 'var(--green)',
                                };
                            @endphp

                            <div class="w-bar">
                                <div class="w-bar-fill"
                                    style="width:{{ $persen }}%; background: {{ $barColor }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nasabah Aktif</div>
                            <div class="w-m-val">{{ $data['totalNasabah']['today'] }}</div>
                            @php
                                $diff = $data['totalNasabah']['difference'];
                            @endphp

                            <div class="w-m-delta {{ $diff >= 0 ? 'up' : 'down' }}">
                                <i class="bi bi-arrow-{{ $diff >= 0 ? 'up' : 'down' }}-short"></i>

                                @if ($diff == 0)
                                    Tidak ada perubahan
                                @else
                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }} nasabah
                                    {{ $diff >= 0 ? 'baru' : 'lebih sedikit' }}
                                @endif
                            </div>
                            @php
                                $persen = $data['totalBeratSetoran']['persentase'];
                                $barColor = match (true) {
                                    $persen < 50 => 'var(--red)',
                                    $persen < 75 => 'var(--orange)',
                                    default => 'var(--green)',
                                };
                            @endphp

                            <div class="w-bar">
                                <div class="w-bar-fill"
                                    style="width:{{ $persen }}%; background: {{ $barColor }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nilai Penarikan Nasabah</div>
                            <div class="w-m-val">
                                {{ convertRupiahToString($data['totalPenarikanSaldoNasabah']['today']) }}
                            </div>
                            @php
                                $persentase = $data['totalPenarikanSaldoNasabah']['persentase'];
                                $arah = match (true) {
                                    $persentase > 0 => 'up',
                                    $persentase < 0 => 'down',
                                    default => 'neutral',
                                };
                            @endphp

                            <div class="w-m-delta {{ $arah }}">
                                @if ($arah === 'up')
                                    <i class="bi bi-arrow-up-short"></i>+{{ $persentase }}% kemarin
                                @elseif ($arah === 'down')
                                    <i class="bi bi-arrow-down-short"></i>{{ $persentase }}% kemarin
                                @else
                                    <i class="bi bi-dash"></i> Sama seperti kemarin
                                @endif
                            </div>
                            @php
                                $persen = $data['totalPenarikanSaldoNasabah']['persentase'];
                                $barColor = match (true) {
                                    $persen < 50 => 'var(--red)',
                                    $persen < 75 => 'var(--orange)',
                                    default => 'var(--green)',
                                };
                            @endphp

                            <div class="w-bar">
                                <div class="w-bar-fill"
                                    style="width:{{ $persen }}%; background: {{ $barColor }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-5">
                        <div class="w-panel h-100">
                            <div class="w-panel-title">Menu Cepat</div>
                            <div class="row g-2">
                                <div class="col-3"><a class="w-svc" onclick="wNav('w-setoran')">
                                        <div class="w-svc-icon ic1"><i class="bi bi-recycle"></i></div><span
                                            class="w-svc-lbl">Setoran</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" onclick="wNav('w-nasabah')">
                                        <div class="w-svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                                            class="w-svc-lbl">Nasabah</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" onclick="wNav('w-harga')">
                                        <div class="w-svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                                            class="w-svc-lbl">Harga</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" onclick="wNav('w-laporan')">
                                        <div class="w-svc-icon ic4"><i class="bi bi-graph-up-arrow"></i></div><span
                                            class="w-svc-lbl">Laporan</span>
                                    </a></div>
                            </div>
                            <div class="w-panel-title mt-3">Transaksi Penarikan Terbaru</div>
                            <div class="d-flex flex-column gap-2">
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic2">
                                        <div class="avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                                            SR</div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Hendra Wijaya — 8 kg Kertas</div>
                                        <div class="w-row-meta">Unit Tampan · 30 mnt lalu · Rp24.000</div>
                                    </div><span class="bs bs-green">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="w-panel h-100">
                            <div class="w-panel-title">Setoran Masuk Terbaru</div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($data['setoranTerbaru'] as $stb)
                                    <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                        <div class="w-row-ico ic1"><i class="bi bi-recycle" style="font-size:13px"></i>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="w-row-title">{{ ucfirst($stb->penyetor->name) }} —
                                                {{ number_format($stb->total_berat, 0, ',', '.') }} kg</div>
                                            <div class="w-row-meta">
                                                @foreach ($stb->penyetor->bukutabungans as $buku)
                                                    {{ $buku->bank->nama }}
                                                @endforeach ·
                                                {{ $stb->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                                <b>
                                                    @foreach ($stb->penyetor->bukutabungans as $buku)
                                                        {{ $buku->nomor_rekening }}
                                                    @endforeach
                                                </b>
                                            </div>
                                        </div><span class="bs bs-green">Rp
                                            {{ number_format($stb->total_saldo, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
