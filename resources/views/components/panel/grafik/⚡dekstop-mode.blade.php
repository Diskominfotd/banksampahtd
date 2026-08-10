<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- An unexamined life is not worth living. - Socrates --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-laporan" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Laporan & Analitik
                        </div>
                        <div style="font-size:11px;color:var(--muted)">Rekap bulanan — Januari s/d Mei 2026</div>
                    </div>
                    {{-- <div class="d-flex gap-2">
                        <button class="w-btn w-btn-ghost" style="font-size:11px"><i class="bi bi-download me-1"></i>Unduh
                            PDF</button>
                        <button class="w-btn w-btn-primary" style="font-size:11px"><i
                                class="bi bi-printer me-1"></i>Cetak Laporan</button>
                    </div> --}}
                </div>
                <div class="row g-3">
                    <div class="col-8">
                        <div class="w-panel" wire:key="grafik-total-sampah-{{ $tahunTotalSampah }}"
                            x-data="{
                                months: {{ Js::from($data['grafik']->pluck('bulan')) }},
                                kgData: {{ Js::from($data['grafik']->pluck('total_berat')) }},
                                nilaiData: {{ Js::from($data['grafik']->pluck('total_saldo')) }},
                                activeIdx: null,
                                get maxKg() { return Math.max(...this.kgData) },
                                get maxNilai() { return Math.max(...this.nilaiData) },
                                barH(val, max) { return val ? Math.round((val / max) * 110) : 4 },
                                opacity(val) { return val ? '.9' : '.25' },
                                fmtKg(val) { return val >= 1000 ? (val / 1000).toFixed(1) + ' ton' : val + ' kg' },
                                fmtNilai(val) {
                                    if (val >= 1000000) return 'Rp' + (val / 1000000).toFixed(1) + 'jt';
                                    if (val >= 1000) return 'Rp' + (val / 1000).toFixed(0) + 'rb';
                                    return 'Rp' + val;
                                }
                            }">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title">Total Sampah Masuk per Bulan (kg & nilai)</div>
                                <select wire:model.live="tahunTotalSampah" class="form-select form-select-sm"
                                    style="font-size:11px;width:auto">
                                    @foreach ($data['komposisi']['tahuns'] as $t)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-cb" style="height:140px">
                                <template x-for="(m, i) in months" :key="i">
                                    <div class="w-cb-col" style="position:relative" x-on:mouseenter="activeIdx = i"
                                        x-on:mouseleave="activeIdx = null">

                                        {{-- Tooltip --}}
                                        <div x-show="activeIdx === i"
                                            style="position:absolute;bottom:calc(100% + 4px);left:50%;transform:translateX(-50%);
                               background:#1a1a1a;color:#fff;border-radius:6px;padding:5px 8px;
                               font-size:9px;white-space:nowrap;z-index:10;line-height:1.6;text-align:center">
                                            <div x-text="fmtKg(kgData[i])"></div>
                                            <div x-text="fmtNilai(nilaiData[i])"></div>
                                        </div>

                                        <div style="display:flex;gap:2px;align-items:flex-end;flex:1;width:100%">
                                            <div :style="`flex:1;height:${barH(kgData[i], maxKg)}px;background:var(--cyan);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         border-radius:3px 3px 0 0;opacity:${opacity(kgData[i])};
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         transition:opacity .15s`"
                                                :class="activeIdx === i ? 'opacity-100' : ''">
                                            </div>
                                            <div
                                                :style="`flex:1;height:${barH(nilaiData[i], maxNilai)}px;background:var(--cyan-10);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     border-radius:3px 3px 0 0;opacity:${opacity(nilaiData[i])};
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     transition:opacity .15s`">
                                            </div>
                                        </div>
                                        <span class="w-cb-lbl" x-text="m"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="d-flex gap-3 mt-2">
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px;height:10px;border-radius:2px;background:var(--cyan)"></div>
                                    <span style="font-size:9px;color:var(--muted)">Berat (kg)</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <div style="width:10px;height:10px;border-radius:2px;background:var(--cyan-10)"></div>
                                    <span style="font-size:9px;color:var(--muted)">Nilai (Rp)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title mb-0">Ringkasan Tahun {{ $tahunTotalRingkasan }}</div>
                                <select wire:model.live="tahunTotalRingkasan" class="form-select form-select-sm"
                                    style="font-size:11px;width:auto">
                                    @foreach ($data['komposisi']['tahuns'] as $t)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <div
                                        style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px">
                                        Total Sampah</div>
                                    <div
                                        style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:var(--cyan)">
                                        {{ convertBeratToString($data['ringkasan']['total_berat']) }}
                                    </div>
                                </div>
                                <div>
                                    <div
                                        style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px">
                                        Total Nilai</div>
                                    <div
                                        style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:var(--blue)">
                                        {{ convertRupiahToString($data['ringkasan']['total_penarikan']) }}</div>
                                    <div style="font-size:10px;color:var(--muted)">Dibayarkan ke nasabah</div>
                                </div>
                                <div>
                                    <div
                                        style="font-size:9px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px">
                                        Nasabah Terlayani</div>
                                    <div
                                        style="font-family:'Syne',sans-serif;font-size:22px;font-weight:700;color:var(--orange)">
                                        {{ $data['nasabah']['total'] }}</div>
                                    <div style="font-size:10px;color:var(--muted)">+
                                        {{ $data['nasabah']['difference'] }}
                                        nasabah baru
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="w-panel">
                            <div class="w-panel-title">5 Top Nasabah</div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($data['topNasabah']['top_nasabah'] as $i => $tpn)
                                    <div class="d-flex align-items-center gap-2 p-2"
                                        style="background:var(--bg-deep);border:1px solid var(--border);border-radius:10px">
                                        <span
                                            style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--cyan);width:20px">
                                            {{ $i + 1 }}
                                        </span>
                                        <div class="flex-grow-1">
                                            <div style="font-size:11px;font-weight:600">{{ ucfirst($tpn->name) }}</div>
                                            <div style="font-size:9px;color:var(--muted)">
                                                {{ convertBeratToString((float) $tpn->setorans_sum_total_berat) }}
                                                · {{ $data['topNasabah']['unit']->nama ?? '-' }}
                                            </div>
                                        </div>
                                        <span style="font-weight:700;font-size:11px;color:var(--cyan)">
                                            {{ convertRupiahToString((int) $tpn->setorans_sum_total_saldo) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="w-panel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="w-panel-title mb-0">Komposisi Sampah</div>
                                <div class="d-flex gap-1">
                                    <select wire:model.live="tahun" class="form-select form-select-sm"
                                        style="font-size:11px;width:auto">
                                        @foreach ($data['komposisi']['tahuns'] as $t)
                                            <option value="{{ $t }}">{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    <select wire:model.live="bulan" class="form-select form-select-sm"
                                        style="font-size:11px;width:auto">
                                        <option value="">Semua Bulan</option>
                                        @foreach (range(1, 12) as $b)
                                            <option value="{{ $b }}">
                                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($data['komposisi']['kategori'] as $kmp)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span style="font-size:11px;font-weight:600">{{ $kmp['nama'] }}</span>
                                            <span
                                                style="font-size:10px;color:var(--blue);font-weight:700">{{ convertBeratToString($kmp['total']) }}</span>
                                        </div>
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
