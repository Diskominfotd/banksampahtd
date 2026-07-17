<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
    <style>
        .chevron-btn {
            background: none;
            border: none;
            padding: 2px 4px;
            cursor: pointer;
            color: var(--text-muted, #9ca3af);
            display: flex;
            align-items: center;
        }

        .chevron-btn i {
            transition: transform 0.2s ease;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .detail-table th {
            text-align: left;
            font-weight: 500;
            color: #6b7280;
            padding: 5px 8px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }
    </style>
    @if (Auth::user()->hasRole(['admin', 'supervisor']))
        {{-- AdminSupervisor --}}
        <div id="m-nasabah">
            <div class="m-header">
                <div class="m-topbar">
                    <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                        <div class="avatar avatar-md">
                            @if (Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
                            @else
                                {{ strtoupper(Auth::user()->initials()) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat
                                datang</strong>
                            </div>
                            <div style="font-size:14px;font-weight:600;color:#fff">
                                <strong>{{ ucfirst(Auth::user()->name) }}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="m-gear" onclick="mNav('m-notifikasi')"><i class="bi bi-bell-fill"></i><span
                            style="position:absolute;top:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:var(--red);border:2px solid rgba(255,255,255,.4);font-size:7px;display:flex;align-items:center;justify-content:center;font-weight:700">3</span>
                    </div> --}}
                </div>
                <div class="m-summary fade-up">
                    <div class="m-summary-lbl">Total Berat Sampah</div>
                    <div class="m-summary-num">
                        {{ number_format($data['totalBeratSetoran']['total'], 0, ',', '.') }} Kg

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
                        @php
                            $summaryArah = fn($p) => match (true) {
                                $p > 0 => 'up',
                                $p < 0 => 'down',
                                default => 'neutral',
                            };
                        @endphp

                        {{-- Nasabah --}}
                        @php
                            $diff = $data['totalNasabah']['difference'];
                        @endphp
                        <div class="m-pill c">
                            <span class="m-pill-n">{{ $data['totalNasabah']['total'] }}</span>
                            <span class="m-pill-l">Nasabah</span>
                            <span class="m-pill-l {{ $diff < 0 ? 'text-danger' : '' }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $diff >= 0 ? 'up' : 'down' }}-short"></i>
                                {{ $diff == 0 ? 'Sama' : ($diff > 0 ? '+' : '') . $diff }}
                            </span>
                        </div>

                        {{-- Setoran --}}
                        @php
                            $arah = $summaryArah($data['totalSaldoSetoran']['persentase']);
                            $persen = $data['totalSaldoSetoran']['persentase'];
                        @endphp
                        <div class="m-pill c">
                            <span
                                class="m-pill-n">{{ number_format($data['totalSaldoSetoran']['total'], 0, ',', '.') }}</span>
                            <span class="m-pill-l">Setoran</span>
                            <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
                            </span>
                        </div>

                        {{-- Tarik --}}
                        @php
                            $arah = $summaryArah($data['totalPenarikanSaldoNasabah']['persentase']);
                            $persen = $data['totalPenarikanSaldoNasabah']['persentase'];
                        @endphp
                        <div class="m-pill">
                            <span
                                class="m-pill-n">{{ number_format($data['totalPenarikanSaldoNasabah']['total'], 0, ',', '.') }}</span>
                            <span class="m-pill-l">Tarik</span>
                            <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-body" style="padding-top:16px">
                <div class="sec-lbl">Menu Utama</div>
                <div class="row g-2">
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('setoran') }}">
                            <div class="svc-icon ic1"><i class="bi bi-recycle"></i>
                                {{-- <div class="notif-dot">5</div> --}}
                            </div><span class="svc-lbl">Setoran</span>
                        </a>
                    </div>
                    @if (Auth::user()->hasRole(['supervisor']))
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('nasabah') }}">
                                <div class="svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                                    class="svc-lbl">Nasabah</span>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->hasRole(['admin']))
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('gudang') }}">
                                <div class="svc-icon ic2"><i class="bi bi-trash3-fill"></i></div><span
                                    class="svc-lbl">Gudang</span>
                            </a>
                        </div>
                    @endif
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                            <div class="svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                                class="svc-lbl">Harga</span>
                        </a>
                    </div>
                    <div class="col-3 fade-up"><a class="svc-item" href="{{ route('penarikan.saldo') }}">
                            <div class="svc-icon ic5"><i class="bi bi-cash-coin"></i></div><span
                                class="svc-lbl">Penarikan</span>
                        </a>
                    </div>
                </div>
                @if (Auth::user()->hasRole(['supervisor']))
                    <div class="row g-2 mt-1">
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('kategori') }}">
                                <div class="svc-icon ic4"><i class="bi bi-grid-fill"></i></div><span
                                    class="svc-lbl">Kategori</span>
                            </a>
                        </div>
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                                <div class="svc-icon ic3"><i class="bi bi-graph-up"></i></div><span
                                    class="svc-lbl">Laporan</span>
                            </a>
                        </div>
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('unit') }}">
                                <div class="svc-icon ic2"> <i class="bi bi-bank"></i></div><span
                                    class="svc-lbl">Bank/Unit</span>
                            </a>
                        </div>
                        <div class="col-3 fade-up"><a class="svc-item" href="{{ route('organisasi') }}">
                                <div class="svc-icon ic2"> <i class="bi bi-building-check"></i></div><span
                                    class="svc-lbl">Organisasi</span>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="sec-lbl">Setoran Hari Ini</div>
                <div class="row g-2">
                    @forelse ($this->getData()['setoranTerbaru'] ?? [] as $stl)
                        <div class="tx-card d-flex align-items-start gap-2 fade-up"
                            wire:click="setoranDetail('{{ encrypt($stl->id) }}')"
                            @click="$store.sheet.show('detail-setoran-id')">
                            <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)">
                                <i class="bi bi-recycle" style="font-size:14px"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="tx-name text-truncate">{{ ucfirst($stl->penyetor->name) }} —
                                    {{ number_format($stl->total_berat, 1, ',', '.') }} Kg</div>
                                <div class="tx-date">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $stl->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                    {{ $stl->bukutabungan->bank->nama }}
                                </div>
                            </div>
                            <span class="bs bs-green flex-shrink-0">
                                Rp {{ number_format($stl->total_saldo, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada Data
                        </div>
                    @endforelse
                </div>
                <div class="sec-lbl">Penarikan Hari Ini</div>
                <div class="row g-2 mb-3">
                    @forelse ($this->getData()['transaksiTerbaru'] ?? [] as $trxl)
                        <div class="tx-card d-flex align-items-start gap-2 fade-up"
                            wire:click="trxDetail('{{ encrypt($trxl->id) }}')"
                            @click="$store.sheet.show('detail-trx-id')">
                            <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)">
                                <i class="bi bi-cash-coin" style="font-size:14px"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="tx-name text-truncate">{{ ucfirst($trxl->owner->name) }} —
                                    {{ $trxl->bukutabungan->nomor_rekening }}</div>
                                <div class="tx-date">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $trxl->created_at->timezone('Asia/Jakarta')->diffForHumans() }} ·
                                    {{ $trxl->bukutabungan->bank->nama }}<br>
                                    <strong>Sisa - Rp {{ number_format($trxl->sisa_saldo, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                            <span class="bs bs-green flex-shrink-0">
                                Rp {{ number_format($trxl->total_penarikan, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align:center;padding:20px 0;color:var(--text-muted,#9ca3af);font-size:13px">
                            <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:6px"></i>
                            Belum ada Data
                        </div>
                    @endforelse
                </div>
            </div>
            @include('components.⚡mobile-bottombar')
        </div>
        {{-- Detail setoran id --}}
        <div x-show="$store.sheet.is('detail-setoran-id')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail setoran id  --}}
        <div x-show="$store.sheet.is('detail-setoran-id')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Detail Setoran - {{ $itemSetoranDetail->kode ?? 'STR-XXX-XXX-XXX' }}
                </div>
            </div>
            <div wire:loading.flex wire:target="setoranDetail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            @if ($itemSetoranDetail)
                <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
                    <div
                        style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                        <div
                            style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                            Nilai Setoran</div>
                        <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">
                            Rp. {{ number_format($itemSetoranDetail->total_saldo, 0, ',', '.') ?? 0 }}
                        </div>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px">
                            <b> Petugas - {{ ucfirst($itemSetoranDetail->admin->name) }} </b>
                        </div>
                    </div>
                    <div style="border:1px solid var(--cyan-bd);border-radius:12px;overflow:hidden;font-size:12px">
                        <table style="width:100%;border-collapse:collapse">
                            <thead>
                                <tr style="background:var(--cyan-10);border-bottom:1px solid var(--cyan-bd)">
                                    <th style="padding:8px 10px;text-align:left;color:var(--muted);font-weight:600">#
                                    </th>
                                    <th style="padding:8px 10px;text-align:left;color:var(--muted);font-weight:600">
                                        Jenis
                                        Sampah</th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Harga
                                    </th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Berat
                                    </th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemSetoranDetail['items'] ?? [] as $index => $di)
                                    <tr style="border-bottom:1px solid var(--cyan-bd)">
                                        <td style="padding:8px 10px;color:var(--muted)">{{ $loop->iteration }}</td>
                                        <td style="padding:8px 10px;font-weight:600">{{ $di['trash']['nama'] }}</td>
                                        <td style="padding:8px 10px;text-align:right">Rp.
                                            {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                        <td style="padding:8px 10px;text-align:right">
                                            {{ number_format($di['berat'], 0, ',', '.') }} Kg</td>
                                        <td
                                            style="padding:8px 10px;text-align:right;color:var(--cyan);font-weight:600">
                                            Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:var(--cyan-10);border-top:1px solid var(--cyan-bd)">
                                    <td colspan="3" style="padding:8px 10px;font-weight:700">Total</td>
                                    <td style="padding:8px 10px;text-align:right;font-weight:700">
                                        {{ number_format($itemSetoranDetail['total_berat'] ?? 0, 0, ',', '.') }}
                                        Kg
                                    </td>
                                    <td style="padding:8px 10px;text-align:right;font-weight:700;color:var(--cyan)">
                                        Rp.
                                        {{ number_format($itemSetoranDetail['total_saldo'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        {{-- Detail trx id --}}
        <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail trx id --}}
        <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Detail Penarikan {{ $itemTrxDetail->kode ?? 'TRX-XXX-XXX-XX' }}
                </div>
            </div>
            <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
                <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                @if ($itemTrxDetail)
                    <div
                        style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                        <div
                            style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                            Nilai Penarikan</div>
                        <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">Rp
                            {{ number_format($itemTrxDetail->total_penarikan, 0, ',', '.') ?? 0 }}</div>
                    </div>
                    <div class="detail-field"><span class="df-key">No. Transaksi</span><span
                            class="df-val">{{ $itemTrxDetail->kode ?? '-' }}</span></div>
                    <div class="detail-field"><span class="df-key">Nasabah</span>
                        <span class="df-val">{{ ucfirst($itemTrxDetail->owner->name) }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Sisa Saldo</span><span class="df-val">
                            Rp {{ number_format($itemTrxDetail->sisa_saldo, 0, ',', '.') ?? 0 }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                            {{ $itemTrxDetail->created_at->format('Y-m-d') }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Unit</span>
                        <span class="df-val">
                            {{ $itemTrxDetail->bukutabungan->bank->nama }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Petugas</span>
                        <span class="df-val">
                            {{ ucfirst($itemTrxDetail->admin->name) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- Nasabah --}}
        <div id="m-beranda">
            <div class="m-header">
                <div class="m-topbar">
                    <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                        <div class="avatar avatar-md" wire:click="movePage('profile')">{{ Auth::user()->initials() }}
                        </div>
                        <div>
                            <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat datang
                            </div>
                            <div style="font-size:14px;font-weight:600;color:#fff">{{ ucfirst(Auth::user()->name) }}
                            </div>
                        </div>
                    </div>
                    <div class="m-gear" wire:click="logout">
                        <i class="bi bi-box-arrow-left"></i>
                    </div>
                </div>
                @php
                    $summaryArah = fn($p) => match (true) {
                        $p > 0 => 'up',
                        $p < 0 => 'down',
                        default => 'neutral',
                    };
                @endphp

                <div class="m-summary fade-up">
                    <div class="m-summary-lbl">Total Saldo Anda</div>

                    @php
                        $arah = $summaryArah($data['totalSaldoNasabah']['persentase']);
                        $persen = $data['totalSaldoNasabah']['persentase'];
                    @endphp
                    <div class="m-summary-num">
                        Rp {{ number_format($data['totalSaldoNasabah']['today'], 0, ',', '.') }}
                        <i class="bi {{ $arah === 'down' ? 'bi-arrow-down-short text-danger' : ($arah === 'up' ? 'bi-arrow-up-short' : 'bi-dash') }}"
                            style="font-size:0.6em;"></i>
                        <span class="m-summary-percent {{ $arah === 'down' ? 'text-danger' : '' }}"
                            style="font-size:0.5em;">
                            {{ $arah === 'neutral' ? 'Sama' : number_format(abs($persen), 1, ',', '.') . '%' }}
                        </span>
                    </div>
                    <div class="m-pills">
                        <div class="m-pill c" wire:click="getBukuTabungan"
                            @click="$store.sheet.show('detail-saldo')">
                            <span class="m-pill-n">{{ $data['totalRekeningNasabah'] }}</span>
                            <span class="m-pill-l">Rekening</span>
                        </div>

                        @php
                            $arah = $summaryArah($data['totalSetoranNasabah']['persentase']);
                            $persen = $data['totalSetoranNasabah']['persentase'];
                        @endphp
                        <div class="m-pill c">
                            <span
                                class="m-pill-n">{{ number_format($data['totalSetoranNasabah']['total'], 0, ',', '.') }}</span>
                            <span class="m-pill-l">Setoran</span>
                            <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
                            </span>
                        </div>

                        @php
                            $arah = $summaryArah($data['totalPenarikanNasabah']['persentase']);
                            $persen = $data['totalPenarikanNasabah']['persentase'];
                        @endphp
                        <div class="m-pill">
                            <span
                                class="m-pill-n">{{ number_format($data['totalPenarikanNasabah']['today'], 0, ',', '.') }}</span>
                            <span class="m-pill-l">Tarik</span>
                            <span class="m-pill-l {{ $arah === 'down' ? 'text-danger' : '' }}"
                                style="display:inline-flex; align-items:center; gap:1px;">
                                <i class="bi bi-arrow-{{ $arah === 'down' ? 'down' : 'up' }}-short"></i>
                                {{ $arah === 'neutral' ? 'Sama' : ($arah === 'up' ? '+' : '') . $persen . '%' }}
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
                            </div><span class="svc-lbl">Rekening</span>
                        </a>
                    </div>
                    <div class="col-3 fade-up"><a class="svc-item" wire:click="getBukuTabungan"
                            @click="$store.sheet.show('detail-saldo')">
                            <div class="svc-icon ic2"><i class="bi bi-wallet"></i></div><span
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
                            <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span
                                class="svc-lbl">Penarikan</span>
                        </a>
                    </div>
                </div>
                <div class="sec-lbl">Setoran Terbaru</div>
                <div class="row g-2">
                    @if ($data['setoranNasabahByLimit']->isNotEmpty())
                        @foreach ($data['setoranNasabahByLimit'] as $snbl)
                            <div class="list-item fade-up" wire:click="setoranDetail('{{ encrypt($snbl->id) }}')"
                                @click="$store.sheet.show('detail-setoran-id')">
                                <span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                                <div class="list-main">
                                    <div class="list-name">{{ $snbl->created_at->format('d M Y') }} —
                                        {{ number_format($snbl->total_berat, 1, ',', '.') }} Kg
                                    </div>
                                    <div class="list-sub">{{ $snbl->bukutabungan->bank->nama }} ·
                                        {{ $snbl->created_at->diffForHumans() }}
                                    </div>
                                </div><span class="bs bs-green">
                                    + Rp
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
                            <div class="list-item fade-up" wire:click="trxDetail('{{ encrypt($trxbl->id) }}')"
                                @click="$store.sheet.show('detail-trx-id')">
                                <span class="list-num">{{ $loop->iteration }}</span>
                                <div class="list-ico ic2"><i class="bi bi-wallet2" style="font-size:12px"></i>
                                </div>
                                <div class="list-main">
                                    <div class="list-name">{{ $trxbl->created_at->format('d M Y') }} —
                                        {{ $trxbl->bukutabungan->nomor_rekening }}
                                    </div>
                                    <div class="list-sub">{{ $trxbl->bukutabungan->bank->nama }} ·
                                        {{ $trxbl->created_at->diffForHumans() }} · Rp
                                        <b> Sisa - {{ number_format($trxbl->sisa_saldo, 0, ',', '.') }}</b>
                                    </div>
                                </div><span class="bs bs-err" style="cursor:pointer">
                                    - Rp {{ number_format($trxbl->total_penarikan, 0, ',', '.') }}
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
                            <div class="list-item"
                                x-data="{ open: false }"style="display: flex;flex-direction: column;
                                    padding: 10px 12px;border-radius: 10px;background: var(--color-background-primary, #fff);border: 0.5px solid #e5e7eb;margin-bottom: 6px;">
                                {{-- Baris atas --}}
                                <div style="display:flex;align-items:center;gap:10px;width:100%">
                                    <span class="list-num">{{ $loop->iteration }}</span>
                                    <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i>
                                    </div>
                                    <div class="list-main">
                                        <div class="list-name">{{ $stn->created_at->format('d M Y') }} —
                                            {{ number_format($stn->total_berat, 1, ',', '.') }} Kg
                                        </div>
                                        <div class="list-sub">{{ $stn->bukutabungan->bank->nama }} ·
                                            {{ $stn->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span class="bs bs-green">Rp
                                        {{ number_format($stn->total_saldo, 0, ',', '.') }}</span>
                                    <button @click="open = !open" class="chevron-btn" :aria-expanded="open">
                                        <i class="bi bi-chevron-down" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                </div>

                                {{-- Baris bawah: tabel detail --}}
                                <div x-show="open" x-collapse
                                    style="width:100%;margin-top:8px;padding-top:8px;border-top:0.5px solid #e5e7eb;">
                                    <table class="detail-table">
                                        <thead>
                                            <tr>
                                                <th>Jenis</th>
                                                <th>Berat</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stn->items as $detail)
                                                <tr>
                                                    <td>{{ $detail->trash->nama }}</td>
                                                    <td>{{ number_format($detail->berat, 1, ',', '.') }} kg</td>
                                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"
                                                    style="padding-top:6px;border-top:0.5px solid #e5e7eb;font-size:12px;color:#6b7280;">
                                                    Total: {{ number_format($stn->total_berat, 1, ',', '.') }} kg
                                                </td>
                                                <td colspan="2"
                                                    style="padding-top:6px;border-top:0.5px solid #e5e7eb;text-align:right;font-weight:500;color:#198754;font-size:12px;">
                                                    Rp {{ number_format($stn->total_saldo, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
                    Penarikan Anda
                </div>
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
                            <div class="list-item fade-up"><span class="list-num">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="list-ico ic2"><i class="bi bi-wallet2" style="font-size:12px"></i>
                                </div>
                                <div class="list-main">
                                    <div class="list-name">
                                        {{ $trx->created_at->format('d M Y') }} —
                                        {{ $trx->bukutabungan->nomor_rekening }}
                                    </div>
                                    <div class="list-name">
                                        Petugas - {{ ucfirst($trx->admin->name) }}
                                    </div>
                                    <div class="list-sub">{{ $trx->bukutabungan->bank->nama }} ·
                                        {{ $trx->created_at->diffForHumans() }} · Rp
                                        <b> Sisa - {{ number_format($trx->sisa_saldo, 0, ',', '.') }}</b>
                                    </div>
                                </div><span class="bs bs-err" style="cursor:pointer">
                                    Rp - {{ number_format($trx->total_penarikan, 0, ',', '.') }}
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
        {{-- Detail setoran id --}}
        <div x-show="$store.sheet.is('detail-setoran-id')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail setoran id  --}}
        <div x-show="$store.sheet.is('detail-setoran-id')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Detail Setoran - {{ $itemSetoranDetail->kode ?? 'STR-XXX-XXX-XXX' }}
                </div>
            </div>
            <div wire:loading.flex wire:target="setoranDetail" class="justify-content-center align-items-center"
                style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                <div class="spinner-border text-success"></div>
            </div>
            @if ($itemSetoranDetail)
                <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
                    <div
                        style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                        <div
                            style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                            Nilai Setoran</div>
                        <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">
                            Rp. {{ number_format($itemSetoranDetail->total_saldo, 0, ',', '.') ?? 0 }}
                        </div>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px">
                            <b> Petugas - {{ ucfirst($itemSetoranDetail->admin->name) }} </b>
                        </div>
                    </div>
                    <div style="border:1px solid var(--cyan-bd);border-radius:12px;overflow:hidden;font-size:12px">
                        <table style="width:100%;border-collapse:collapse">
                            <thead>
                                <tr style="background:var(--cyan-10);border-bottom:1px solid var(--cyan-bd)">
                                    <th style="padding:8px 10px;text-align:left;color:var(--muted);font-weight:600">#
                                    </th>
                                    <th style="padding:8px 10px;text-align:left;color:var(--muted);font-weight:600">
                                        Jenis
                                        Sampah</th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Harga
                                    </th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Berat
                                    </th>
                                    <th style="padding:8px 10px;text-align:right;color:var(--muted);font-weight:600">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemSetoranDetail['items'] ?? [] as $index => $di)
                                    <tr style="border-bottom:1px solid var(--cyan-bd)">
                                        <td style="padding:8px 10px;color:var(--muted)">{{ $loop->iteration }}</td>
                                        <td style="padding:8px 10px;font-weight:600">{{ $di['trash']['nama'] }}</td>
                                        <td style="padding:8px 10px;text-align:right">Rp.
                                            {{ number_format($di['harga'], 0, ',', '.') }}</td>
                                        <td style="padding:8px 10px;text-align:right">
                                            {{ number_format($di['berat'], 1, ',', '.') }} Kg</td>
                                        <td
                                            style="padding:8px 10px;text-align:right;color:var(--cyan);font-weight:600">
                                            Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:var(--cyan-10);border-top:1px solid var(--cyan-bd)">
                                    <td colspan="3" style="padding:8px 10px;font-weight:700">Total</td>
                                    <td style="padding:8px 10px;text-align:right;font-weight:700">
                                        {{ number_format($itemSetoranDetail['total_berat'] ?? 1, 0, ',', '.') }}
                                        Kg
                                    </td>
                                    <td style="padding:8px 10px;text-align:right;font-weight:700;color:var(--cyan)">
                                        Rp.
                                        {{ number_format($itemSetoranDetail['total_saldo'] ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        {{-- Detail trx id --}}
        <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="$store.sheet.hide()"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:998" x-cloak>
        </div>
        {{-- Sheet Detail trx id --}}
        <div x-show="$store.sheet.is('detail-trx-id')" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="sheet-pilih-sampah" style="display:none" x-cloak>
            <div
                style="flex-shrink:0;padding:16px 20px 12px;border-radius:20px 20px 0 0;background:var(--bg-card,#fff)">
                <div class="sheet-handle"></div>
                <div
                    style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-top:10px;color:var(--text-main)">
                    Detail Penarikan {{ $itemTrxDetail->kode ?? 'TRX-XXX-XXX-XX' }}
                </div>
            </div>
            <div style="flex:1;overflow-y:auto;padding:0 20px 20px;-webkit-overflow-scrolling:touch">
                <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                @if ($itemTrxDetail)
                    <div
                        style="background:var(--red-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:14px;margin-bottom:16px;text-align:center">
                        <div
                            style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                            Nilai Penarikan</div>
                        <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--red)">Rp
                            {{ number_format($itemTrxDetail->total_penarikan, 0, ',', '.') ?? 0 }}</div>
                    </div>
                    <div class="detail-field"><span class="df-key">No. Transaksi</span><span
                            class="df-val">{{ $itemTrxDetail->kode ?? '-' }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Nasabah</span>
                        <span class="df-val">{{ ucfirst($itemTrxDetail->owner->name) }}</span>
                    </div>
                    <div class="detail-field">
                        <span class="df-key">Sisa Saldo</span><span class="df-val">
                            Rp {{ number_format($itemTrxDetail->sisa_saldo, 0, ',', '.') ?? 0 }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                            {{ $itemTrxDetail->created_at->format('Y-m-d') }}
                        </span>
                    </div>
                    <div class="detail-field"><span class="df-key">Unit</span>
                        <span class="df-val">
                            {{ $itemTrxDetail->bukutabungan->bank->nama }}</span>
                    </div>
                    <div class="detail-field"><span class="df-key">Petugas</span>
                        <span class="df-val">
                            {{ ucfirst($itemTrxDetail->admin->name) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
