<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh --}}
    {{-- <div class="desktop-wrapper">
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
                                <div class="col-3"><a class="w-svc" href="{{ route('setoran') }}">
                                        <div class="w-svc-icon ic1"><i class="bi bi-recycle"></i></div><span
                                            class="w-svc-lbl">Setoran</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" href="{{ route('nasabah') }}">
                                        <div class="w-svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                                            class="w-svc-lbl">Nasabah</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" href="{{ route('harga') }}">
                                        <div class="w-svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                                            class="w-svc-lbl">Harga</span>
                                    </a></div>
                                <div class="col-3"><a class="w-svc" href="{{ route('buat.penarikan.saldo') }}">
                                        <div class="w-svc-icon ic4"><i class="bi bi-cash-coin"></i></div><span
                                            class="w-svc-lbl">Buat Penarikan</span>
                                    </a></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title mt-3">Transaksi Penarikan Terbaru</div>
                                <span wire:click="movePage('penarikan.saldo')" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">Semua</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($data['transaksiTerbaru'] as $tb)
                                    @php
                                        $owner = $tb->owner ?? null;
                                        $ownerName = $owner->name ?? '-';
                                        $bukutabungan = $owner?->bukutabungans?->first();
                                        $nomorRekening = $bukutabungan->nomor_rekening ?? '-';
                                        $bankNama = $bukutabungan?->bank?->nama ?? '-';
                                        $initials =
                                            $ownerName !== '-'
                                                ? strtoupper(
                                                    substr($ownerName, 0, 1) .
                                                        substr(strrchr(' ' . $ownerName, ' '), 1, 1),
                                                )
                                                : '?';
                                    @endphp
                                    <div class="w-row">
                                        <div class="w-row-ico ic2">
                                            <div class="avatar"
                                                style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                                                {{ $initials }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="w-row-title">
                                                {{ ucfirst($ownerName) }} — {{ $nomorRekening }}
                                            </div>
                                            <div class="w-row-meta">
                                                {{ $bankNama }} ·
                                                {{ $tb->tanggal_transaksi?->diffForHumans() ?? '-' }}
                                            </div>
                                            <div class="w-row-meta">
                                                <b>Sisa - Rp {{ number_format($tb->sisa_saldo ?? 0, 0, ',', '.') }}</b>
                                            </div>
                                        </div>
                                        <span class="bs bs-green">
                                            Rp {{ number_format($tb->total_penarikan ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title">Setoran Masuk Terbaru</div>
                                <span wire:click="movePage('setoran')" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">Semua</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($data['setoranTerbaru'] as $stb)
                                    <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                        <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                                style="font-size:13px"></i>
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
    </div> --}}

    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-dashboard" class="w-content">
                <div class="row g-3">
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Total Saldo Anda</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp
                                {{ convertRupiahToString($data['totalSaldoNasabah']['today']) }}
                            </div>
                            @php
                                $persentase = $data['totalSaldoNasabah']['persentase'];
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
                            <div class="w-m-lbl">Jumlah Rekening</div>
                            <div class="w-m-val" style="color:var(--blue)">{{ $data['totalRekeningNasabah'] }}</div>
                            <div class="w-m-delta up">
                                <a class="bs bs-green" wire:click="getBukuTabungan" data-bs-toggle="modal"
                                    data-bs-target="#wm-rekening-nasabah"
                                    style="font-size: 12px; cursor: pointer;">Rekenig</a>
                            </div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:100%;background:var(--blue)"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nilai Setoran</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp
                                {{ convertRupiahToString($data['totalSetoranNasabah']['today']) }}
                            </div>
                            @php
                                $persentase = $data['totalSetoranNasabah']['persentase'];
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
                            <div class="w-m-lbl">Nilai Penarikan</div>
                            <div class="w-m-val">
                                {{ convertRupiahToString($data['totalPenarikanNasabah']['today']) }}</div>
                            @php
                                $persentase = $data['totalPenarikanNasabah']['persentase'];
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
                                <div class="col-3"><a class="w-svc" href="{{ route('setoran') }}">
                                        <div class="w-svc-icon ic1"><i class="bi bi-bank"></i></div><span
                                            class="w-svc-lbl">Buka Rekening</span>
                                    </a>
                                </div>
                                <div class="col-3"><a class="w-svc" href="{{ route('setoran') }}">
                                        <div class="w-svc-icon ic1"><i class="bi bi-wallet2"></i></div><span
                                            class="w-svc-lbl">Saldo</span>
                                    </a>
                                </div>
                                <div class="col-3"><a class="w-svc" href="{{ route('setoran') }}">
                                        <div class="w-svc-icon ic1"><i class="bi bi-recycle"></i></div><span
                                            class="w-svc-lbl">Setoran</span>
                                    </a>
                                </div>
                                <div class="col-3"><a class="w-svc" href="{{ route('setoran') }}">
                                        <div class="w-svc-icon ic1"><i class="bi bi-cash-stack"></i></div><span
                                            class="w-svc-lbl">Penarikan</span>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title mt-3">Transaksi Penarikan Terbaru</div>
                                <span wire:click="movePage('penarikan.saldo')" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">Semua</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if ($data['trxNasabahByLimit']->isNotEmpty())
                                    @foreach ($data['trxNasabahByLimit'] as $trxbl)
                                        <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                            <div class="w-row-ico ic2"><i class="bi bi-newspaper"
                                                    style="font-size:13px"></i></div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="w-row-title">
                                                    {{ $trxbl->created_at->format('d M Y') }} —
                                                    {{ $trxbl->bukutabungan->nomor_rekening }}</div>
                                                <div class="w-row-meta">{{ $trxbl->bukutabungan->bank->nama }} ·
                                                    {{ $trxbl->created_at->diffForHumans() }} ·
                                                    <b> Sisa - Rp
                                                        {{ number_format($trxbl->sisa_saldo, 0, ',', '.') }}</b>
                                                </div>
                                            </div><span class="bs bs-err">Rp
                                                - {{ number_format($trxbl->total_penarikan, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div
                                        style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                        <i class="bi bi-inbox"
                                            style="font-size:24px;display:block;margin-bottom:6px"></i>
                                        Belum ada data
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title">Setoran Masuk Terbaru</div>
                                <span wire:click="movePage('setoran')" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">Semua</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if ($data['setoranNasabahByLimit']->isNotEmpty())
                                    @foreach ($data['setoranNasabahByLimit'] as $snbl)
                                        <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                            <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                                    style="font-size:13px"></i></div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="w-row-title">{{ $snbl->created_at->format('d M Y') }} —
                                                    {{ number_format($snbl->total_berat, 0, ',', '.') }} Kg</div>
                                                <div class="w-row-meta">{{ $snbl->bukutabungan->bank->nama }} ·
                                                    {{ $snbl->created_at->diffForHumans() }}</div>
                                            </div><span class="bs bs-green">
                                                Rp
                                                + {{ number_format($snbl->total_saldo, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <div
                                        style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                        <i class="bi bi-inbox"
                                            style="font-size:24px;display:block;margin-bottom:6px"></i>
                                        Belum ada data
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="wm-rekening-nasabah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Rekening Nasabah</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body">
                    <form wire:submit="addBukuTabungan">
                        <div class="d-flex flex-column gap-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="w-form-label">Unit</label>
                                    <select class="w-form-input" wire:model="unitBukuTabungan">
                                        <option value="">Pilih Unit</option>
                                        @foreach ($data['banksampah'] as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('unitBukuTabungan')
                                        <small class="text-danger" style="font-size:10px">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                                wire:target="addBukuTabungan">
                                <span wire:loading.remove wire:target="addBukuTabungan">Tambahkan</span>
                                <span wire:loading wire:target="addBukuTabungan">Loading...</span>
                            </button>
                        </div>
                    </form>
                    @if (!empty($bukuTabungan))
                        <div class="mt-3">
                            <label class="w-form-label mb-2">Daftar Buku Tabungan</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($bukuTabungan as $bk)
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2"
                                        style="border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;">
                                        <span style="font-size: 13px; font-weight: 500; color: #111827;">
                                            {{ $bk['bank']['nama'] }}
                                        </span>
                                        <span style="font-size: 12px; color: #6b7280; font-family: monospace;">
                                            {{ $bk['nomor_rekening'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-3 text-center" style="font-size: 13px; color: #9ca3af; padding: 12px 0;">
                            Belum ada buku tabungan
                        </div>
                    @endif

                </div>
                <div class="w-modal-footer">
                </div>
            </div>
        </div>
    </div>





</div>
