<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh --}}

    {{-- Supervisor Admin --}}
    @if (Auth::user()->hasRole(['admin', 'supervisor']))
        <div class="desktop-wrapper">
            @include('components.⚡dekstop-navbar')
            <div class="w-main">
                @include('components.⚡dekstop-header')
                <div id="w-dashboard" class="w-content">
                    <div class="row g-3">
                        <div class="col-3 fade-up">
                            <div class="w-metric">
                                <div class="w-m-lbl">Total Sampah</div>
                                <div class="w-m-val" style="color:var(--cyan)">
                                    {{ number_format($data['totalBeratSetoran']['total'], 1, ',', '.') }} kg
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
                                         default => 'var(--cyan)',
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
                                    {{ number_format($data['totalSaldoSetoran']['total'],0,',','.') }}
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
                                         default => 'var(--cyan)',
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
                                <div class="w-m-val" style="color:var(--blue)">{{ $data['totalNasabah']['total'] }}
                                </div>
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
                                         default => 'var(--cyan)',
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
                                <div class="w-m-val" style="color:var(--blue)">
                                    {{ number_format($data['totalPenarikanSaldoNasabah']['total'],0,',','.') }}
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
                                         default => 'var(--cyan)',
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
                                        </a>
                                    </div>
                                    <div class="col-3"><a class="w-svc" href="{{ route('nasabah') }}">
                                            <div class="w-svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                                                class="w-svc-lbl">Nasabah</span>
                                        </a></div>
                                    <div class="col-3"><a class="w-svc" href="{{ route('harga') }}">
                                            <div class="w-svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                                                class="w-svc-lbl">Harga</span>
                                        </a>
                                    </div>
                                    @if (Auth::user()->hasRole('admin'))
                                        <div class="col-3"><a class="w-svc"
                                                href="{{ route('buat.penarikan.saldo') }}">
                                                <div class="w-svc-icon ic4"><i class="bi bi-cash-coin"></i></div><span
                                                    class="w-svc-lbl">Buat Penarikan</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-panel-title mt-3">Transaksi Penarikan Hari Ini</div>
                                    <span wire:click="movePage('penarikan.saldo')" class="bs bs-green"
                                        style="font-size: 12px; cursor: pointer;">Semua</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @forelse ($data['transaksiTerbaru'] as $tb)
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
                                        <div class="w-row" wire:click="trxDetail('{{ encrypt($tb->id) }}')"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-trx-id">
                                            <div class="w-row-ico ic2">
                                                <div class="avatar"
                                                    style="width:36px;height:36px;font-size:12px;flex-shrink:0">
                                                    {{ $initials }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="w-row-title">{{ ucfirst($ownerName) }} —
                                                    {{ $nomorRekening }}</div>
                                                <div class="w-row-meta">
                                                    {{ $bankNama }} ·
                                                    {{ $tb->tanggal_transaksi?->diffForHumans() ?? '-' }}
                                                </div>
                                                <div class="w-row-meta">
                                                    <b>Sisa - Rp
                                                        {{ number_format($tb->sisa_saldo ?? 0, 0, ',', '.') }}</b>
                                                </div>
                                            </div>
                                            <span class="bs bs-green">
                                                Rp {{ number_format($tb->total_penarikan ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @empty
                                        <div
                                            style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                            <i class="bi bi-inbox"
                                                style="font-size:24px;display:block;margin-bottom:6px"></i>
                                            Belum ada transaksi
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="w-panel h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-panel-title">Setoran Masuk Hari Ini</div>
                                    <span wire:click="movePage('setoran')" class="bs bs-green"
                                        style="font-size: 12px; cursor: pointer;">Semua</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @if ($data['setoranTerbaru']->isNotEmpty())
                                        @foreach ($data['setoranTerbaru'] as $stb)
                                            <div class="w-row" data-bs-toggle="modal"
                                                wire:click="setoranDetail('{{ encrypt($stb->id) }}')"
                                                data-bs-target="#wm-detail-setoran-id">
                                                <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                                        style="font-size:13px"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="w-row-title">{{ ucfirst($stb->penyetor->name) }} —
                                                        {{ number_format($stb->total_berat, 1, ',', '.') }} kg</div>
                                                    <div class="w-row-meta">
                                                        {{ $stb->bukutabungan->bank->nama }} ·
                                                        {{ $stb->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                                        ·
                                                        <b>
                                                            {{ $stb->bukutabungan->nomor_rekening }}
                                                        </b>
                                                    </div>
                                                </div><span class="bs bs-green">Rp
                                                    {{ number_format($stb->total_saldo, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div
                                            style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                            <i class="bi bi-inbox"
                                                style="font-size:24px;display:block;margin-bottom:20px"></i>
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
        <div wire:ignore.self class="modal fade" id="wm-detail-setoran-id" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">Detail Setoran - {{ $itemSetoranDetail->kode ?? '-' }}</div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div wire:loading.flex wire:target="setoranDetail"
                        class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    @if ($itemSetoranDetail)
                        <div class="w-modal-body">
                            <div
                                style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                                <div
                                    style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                    Nilai Setoran</div>
                                <div
                                    style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
                                    Rp
                                    {{ number_format($itemSetoranDetail->total_saldo, 0, ',', '.') ?? 0 }}
                                </div>

                                <div style="font-size:12px;color:var(--muted);margin-top:4px">
                                    <b> Petugas - {{ ucfirst($itemSetoranDetail->admin->name) }} </b>
                                </div>
                            </div>
                            <table class="w-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Sampah</th>
                                        <th>Harga</th>
                                        <th>Berat</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemSetoranDetail['items'] ?? [] as $index => $di)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td style="font-size:11px;font-weight:600">{{ $di['trash']['nama'] }}</td>
                                            <td>Rp. {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($di['berat'], 1, ',', '.') }} KG</td>
                                            <td>Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td>
                                            <strong>
                                                {{ number_format($itemSetoranDetail['total_berat'] ?? 1, 0, ',', '.') }}
                                                KG
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>
                                                Rp.
                                                {{ number_format($itemSetoranDetail['total_saldo'] ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                    <div class="w-modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="wm-detail-trx-id" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">Detail Penarikan - {{ $itemTrxDetail->kode ?? 'TRX-XXX-XXX-XXX' }}
                        </div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    @if ($itemTrxDetail)
                        <div class="w-modal-body">
                            <div
                                style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                                <div
                                    style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                    Nilai Penarikan</div>
                                <div
                                    style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
                                    Rp
                                    {{ number_format($itemTrxDetail->total_penarikan, 0, ',', '.') ?? 0 }}
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="detail-field"><span class="df-key">No. Transaksi</span><span
                                            class="df-val">{{ $itemTrxDetail->kode ?? '-' }}</span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Nasabah</span><span
                                            class="df-val">{{ ucfirst($itemTrxDetail->owner->name) }}</span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Sisa Saldo</span><span
                                            class="df-val">
                                            <b> Rp
                                                {{ number_format($itemTrxDetail->sisa_saldo, 0, ',', '.') ?? 0 }}</b>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-field"><span class="df-key">Tanggal</span><span
                                            class="df-val">
                                            {{ $itemTrxDetail->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Unit</span><span class="df-val">
                                            {{ $itemTrxDetail->bukutabungan->bank->nama }}
                                        </span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Petugas</span><span
                                            class="df-val">
                                            {{ ucfirst($itemTrxDetail->admin->name) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
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
                                    {{ number_format($data['totalSaldoNasabah']['today'], 0,',','.') }}
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
                                        <i class="bi bi-dash"></i> Tidak ada perubahan
                                    @endif
                                </div>
                                @php
                                    $persen = $data['totalBeratSetoran']['persentase'];
                                    $barColor = match (true) {
                                        $persen < 50 => 'var(--red)',
                                        $persen < 75 => 'var(--orange)',
                                         default => 'var(--cyan)',
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
                                <div class="w-m-val" style="color:var(--blue)">{{ $data['totalRekeningNasabah'] }}
                                </div>
                                <div class="w-m-delta up">
                                    <a class="bs bs-green" wire:click="getBukuTabungan" data-bs-toggle="modal"
                                        data-bs-target="#wm-saldo-nasabah"
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
                                    {{ number_format($data['totalSetoranNasabah']['total'], 0,',','.') }}
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
                                        <i class="bi bi-dash"></i> Tidak ada perubahan
                                    @endif
                                </div>
                                @php
                                    $persen = $data['totalBeratSetoran']['persentase'];
                                    $barColor = match (true) {
                                        $persen < 50 => 'var(--red)',
                                        $persen < 75 => 'var(--orange)',
                                         default => 'var(--cyan)',
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
                                    {{ number_format($data['totalPenarikanNasabah']['today'], 0,',','.') }}</div>
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
                                        <i class="bi bi-dash"></i> Tidak ada perubahan
                                    @endif
                                </div>
                                @php
                                    $persen = $data['totalPenarikanSaldoNasabah']['persentase'];
                                    $barColor = match (true) {
                                        $persen < 50 => 'var(--red)',
                                        $persen < 75 => 'var(--orange)',
                                         default => 'var(--cyan)',
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
                                    <div class="col-3">
                                        <a class="w-svc" wire:click="getBukuTabungan" data-bs-toggle="modal"
                                            data-bs-target="#wm-rekening-nasabah">
                                            <div class="w-svc-icon ic1"><i class="bi bi-bank"></i></div><span
                                                class="w-svc-lbl">Buka Rekening</span>
                                        </a>
                                    </div>
                                    <div class="col-3">
                                        <a class="w-svc" wire:click="getBukuTabungan" data-bs-toggle="modal"
                                            data-bs-target="#wm-saldo-nasabah">
                                            <div class="w-svc-icon ic1"><i class="bi bi-wallet"></i></div><span
                                                class="w-svc-lbl">Saldo</span>
                                        </a>
                                    </div>
                                    <div class="col-3"><a class="w-svc" wire:click="getSetoranByAuthUser"
                                            data-bs-toggle="modal" data-bs-target="#wm-setoran-nasabah">
                                            <div class="w-svc-icon ic1"><i class="bi bi-recycle"></i></div><span
                                                class="w-svc-lbl">Setoran</span>
                                        </a>
                                    </div>
                                    <div class="col-3">
                                        <a class="w-svc" wire:click="getTrxByAuthUser" data-bs-toggle="modal"
                                            data-bs-target="#wm-trx-nasabah">
                                            <div class="w-svc-icon ic1"> <i class="bi bi-wallet2"></i></div><span
                                                class="w-svc-lbl">Penarikan</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-panel-title mt-3">Transaksi Penarikan Hari Ini</div>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @if ($data['trxNasabahByLimit']->isNotEmpty())
                                        @foreach ($data['trxNasabahByLimit'] as $trxbl)
                                            <div class="w-row" wire:click="trxDetail('{{ encrypt($trxbl->id) }}')"
                                                data-bs-toggle="modal" data-bs-target="#wm-detail-trx-id">
                                                <div class="w-row-ico ic2">
                                                    <i class="bi bi-wallet2" style="font-size:13px"></i>
                                                </div>
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
                                    <div class="w-panel-title">Setoran Masuk Hari Ini</div>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @if ($data['setoranNasabahByLimit']->isNotEmpty())
                                        @foreach ($data['setoranNasabahByLimit'] as $snbl)
                                            <div class="w-row" data-bs-toggle="modal"
                                                wire:click="setoranDetail('{{ encrypt($snbl->id) }}')"
                                                data-bs-target="#wm-detail-setoran-id">
                                                <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                                        style="font-size:13px"></i></div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="w-row-title">{{ $snbl->created_at->format('d M Y') }}
                                                        —
                                                        {{ number_format($snbl->total_berat, 1, ',', '.') }} Kg</div>
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
        <div wire:ignore.self class="modal fade" id="wm-detail-trx-id" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">Detail Penarikan - {{ $itemTrxDetail->kode ?? 'TRX-XXX-XXX-XXX' }}
                        </div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    @if ($itemTrxDetail)
                        <div class="w-modal-body">
                            <div
                                style="background:var(--red-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                                <div
                                    style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                    Nilai Penarikan</div>
                                <div
                                    style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--red)">
                                    Rp
                                    {{ number_format($itemTrxDetail->total_penarikan, 0, ',', '.') ?? 0 }}
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="detail-field"><span class="df-key">No. Transaksi</span><span
                                            class="df-val">{{ $itemTrxDetail->kode ?? '-' }}</span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Nasabah</span><span
                                            class="df-val">{{ ucfirst($itemTrxDetail->owner->name) }}</span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Sisa Saldo</span><span
                                            class="df-val">
                                            <b> Rp
                                                {{ number_format($itemTrxDetail->sisa_saldo, 0, ',', '.') ?? 0 }}</b>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-field"><span class="df-key">Tanggal</span><span
                                            class="df-val">
                                            {{ $itemTrxDetail->created_at->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Unit</span><span class="df-val">
                                            {{ $itemTrxDetail->bukutabungan->bank->nama }}
                                        </span>
                                    </div>
                                    <div class="detail-field"><span class="df-key">Petugas</span><span
                                            class="df-val">
                                            {{ ucfirst($itemTrxDetail->admin->name) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="wm-detail-setoran-id" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">Detail Setoran -
                            {{ $itemSetoranDetail->kode ?? 'STR-XXX-XXX-XX' }}</div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div wire:loading.flex wire:target="setoranDetail"
                        class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
                    </div>
                    @if ($itemSetoranDetail)
                        <div class="w-modal-body">
                            <div
                                style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                                <div
                                    style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                    Nilai Setoran</div>
                                <div
                                    style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
                                    Rp
                                    {{ number_format($itemSetoranDetail->total_saldo, 0, ',', '.') ?? 0 }}
                                </div>

                                <div style="font-size:12px;color:var(--muted);margin-top:4px">
                                    <b> Petugas - {{ ucfirst($itemSetoranDetail->admin->name) }} </b>
                                </div>
                            </div>
                            <table class="w-tbl">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Sampah</th>
                                        <th>Harga</th>
                                        <th>Berat</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemSetoranDetail['items'] ?? [] as $index => $di)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td style="font-size:11px;font-weight:600">{{ $di['trash']['nama'] }}
                                            </td>
                                            <td>Rp. {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($di['berat'], 1, ',', '.') }} KG</td>
                                            <td>Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td>
                                            <strong>
                                                {{ number_format($itemSetoranDetail['total_berat'] ?? 1, 0, ',', '.') }}
                                                KG
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>
                                                Rp.
                                                {{ number_format($itemSetoranDetail['total_saldo'] ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                    <div class="w-modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="wm-saldo-nasabah" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-m">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">
                            Saldo Anda -
                            <b style="color:#16a34a">
                                Rp
                                {{ number_format(array_sum(array_column($bukuTabungan, 'saldo')), 0, ',', '.') }}</b>
                        </div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div class="w-modal-body">
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
                            <div
                                style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                                <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                                Belum ada buku tabungan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="wm-setoran-nasabah" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">
                            Setoran Anda
                        </div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div class="w-modal-body"
                        style="flex:1;overflow-y:auto;padding:0 10px 10px;-webkit-overflow-scrolling:touch">
                        <div wire:loading.flex wire:target="getSetoranByAuthUser"
                            class="justify-content-center align-items-center"
                            style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                            <div class="spinner-border text-success"></div>
                        </div>
                        <div class="row g-2">
                            @if (!empty($setoranNasabah))
                                @foreach ($setoranNasabah as $stn)
                                    <div class="col-12" x-data="{ open: false }"
                                        wire:key="setoran-{{ $stn->id }}">
                                        <div class="list-item fade-up" style="cursor:pointer" @click="open = !open">
                                            <span class="list-num">{{ $loop->iteration }}</span>

                                            <div class="list-ico ic1">
                                                <i class="bi bi-recycle" style="font-size:12px"></i>
                                            </div>

                                            <div class="list-main">
                                                <div class="list-name">
                                                    {{ $stn->created_at->format('d M Y') }}
                                                    &mdash; {{ number_format($stn->total_berat, 1, ',', '.') }} Kg
                                                    &mdash; Petugas: {{ ucfirst($stn->admin->name) }}
                                                </div>
                                                <div class="list-sub">
                                                    {{ $stn->bukutabungan->bank->nama }}
                                                    &middot; {{ $stn->created_at->diffForHumans() }}
                                                </div>
                                            </div>

                                            <span class="bs bs-green">
                                                + Rp {{ number_format($stn->total_saldo, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        {{-- Detail Drop Down --}}
                                        <div
                                            style="border:1px solid var(--cyan-bd);border-radius:12px 12px 12px 12px;background:var(--cyan-10);padding:14px 16px;margin-top:-2px;font-size:12px">
                                            <div style="display:flex;flex-direction:column;gap:6px">
                                                <table class="w-tbl">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Jenis Sampah</th>
                                                            <th>Harga</th>
                                                            <th>Berat</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($stn->items ?? [] as $index => $di)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td style="font-size:11px;font-weight:600">
                                                                    {{ $di->trash->nama ?? '-' }}</td>
                                                                <td>Rp.
                                                                    {{ number_format($di->harga, 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ number_format($di->berat, 0, ',', '.') }}
                                                                    KG</td>
                                                                <td>Rp.
                                                                    {{ number_format($di->sub_total, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5"
                                                                    style="text-align:center;color:var(--muted)">
                                                                    Tidak ada detail
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3"><strong>Total</strong></td>
                                                            <td>
                                                                <strong>
                                                                    {{ number_format($stn->total_berat ?? 0, 0, ',', '.') }}
                                                                    KG
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                <strong>
                                                                    Rp.
                                                                    {{ number_format($stn->total_saldo ?? 0, 0, ',', '.') }}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
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
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="wm-trx-nasabah" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content w-modal">
                    <div class="w-modal-header">
                        <div class="w-modal-title">
                            Penarikan Anda
                        </div>
                        <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                    </div>
                    <div class="w-modal-body">
                        <div style="flex:1;overflow-y:auto;padding:0 10px 10px;-webkit-overflow-scrolling:touch">
                            <div wire:loading.flex wire:target="getTrxByAuthUser"
                                class="justify-content-center align-items-center"
                                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                                <div class="spinner-border text-success"></div>
                            </div>
                            <div class="row g-2">
                                @if (!empty($trxNasabah))
                                    @foreach ($trxNasabah as $trx)
                                        <div class="list-item fade-up"><span
                                                class="list-num">{{ $loop->iteration }}</span>
                                            <div class="list-ico ic2">
                                                <i class="bi bi-wallet2" style="font-size:12px"></i>
                                            </div>
                                            <div class="list-main">
                                                <div class="list-name">{{ $trx->created_at->format('d M Y') }} —
                                                    {{ $trx->bukutabungan->nomor_rekening }} - Petugas : {{ $trx->admin->name  }}
                                                </div>
                                                <div class="list-sub">{{ $trx->bukutabungan->bank->nama }} ·
                                                    {{ $trx->created_at->diffForHumans() }} ·
                                                    <b> Sisa - Rp
                                                        {{ number_format($trx->sisa_saldo, 0, ',', '.') }}</b>
                                                </div>
                                            </div>
                                            <span class="bs bs-err" style="cursor:pointer">
                                                - Rp {{ number_format($trx->total_penarikan, 0, ',', '.') }}
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
                </div>
            </div>
        </div>
    @endif

</div>
</div>
