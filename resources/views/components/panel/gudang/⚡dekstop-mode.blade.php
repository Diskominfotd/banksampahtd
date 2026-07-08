<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- When there is no desire, all things are at peace. - Laozi --}}
    <div class="desktop-wrapper">
        @include('components.⚡dekstop-navbar')
        <div class="w-main">
            @include('components.⚡dekstop-header')
            <div id="w-dashboard" class="w-content">
                <div class="row g-3">
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Total Stok</div>
                            <div class="w-m-val" style="color:var(--cyan)">
                                {{ number_format($data['totalStokGudang']['total'], 0, ',', '.') }} kg
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
                                    <i class="bi bi-dash"></i> Tidak Ada Perubahan
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
                            <div class="w-m-lbl">Pendapatan</div>
                            <div class="w-m-val" style="color:var(--cyan)">
                                Rp {{ convertRupiahToString($data['totalPendapatan']['total']) }}
                            </div>
                            @php
                                $persentase = $data['totalPendapatan']['persentase'];
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
                                    <i class="bi bi-dash"></i> Tidak Ada Perubahan
                                @endif
                            </div>
                            @php
                                $persen = $data['totalPendapatan']['persentase'];
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
                            <div class="w-m-lbl">Pengeluaran</div>
                            <div class="w-m-val" style="color:var(--blue)">
                                {{ convertRupiahToString($data['totalPenarikanSaldoNasabah']['total']) }}
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
                                    <i class="bi bi-dash"></i> Tidak Ada Perubahan
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
                    <div class="col-6">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="w-panel-title">Transaksi Gudang</div>
                                <span wire:click="isiGudang" data-bs-toggle="modal" data-bs-target="#wm-bongkar-gudang"
                                    class="bs bs-green" style="font-size: 12px; cursor: pointer;">
                                    <i class="bi bi-box-arrow-up"></i> Bongkar Gudang
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @forelse ($data['trx'] as $tb)
                                    <div class="w-row" data-bs-toggle="modal" data-bs-target="#wm-detail-trx"
                                        wire:click="trxDetail('{{ encrypt($tb->id) }}')">
                                        <div class="w-row-ico ic5"><i class="bi bi-box-fill" style="font-size:13px"></i>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="w-row-title">{{ $tb->kode ?? '-' }}</div>
                                            <div class="w-row-meta"><b>{{ ucfirst($tb->admin->name) }}</b> ·
                                                {{ $tb->created_at?->diffForHumans() }}
                                                <span class="bs bs-err"> -
                                                    {{ convertBeratToString($tb->total_berat) }}</span>
                                            </div>
                                        </div>
                                        <span class="bs bs-new">
                                            Rp{{ convertRupiahToString($tb->total_penarikan) }}
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
                            <div class="mt-2">
                                {{ $data['trx']->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-panel-title">Setoran Masuk Hari Ini</div>
                                <span wire:click="movePage('setoran')" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">Semua</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if ($data['setoran']->isNotEmpty())
                                    @foreach ($data['setoran'] as $stb)
                                        <div class="w-row" data-bs-toggle="modal"
                                            wire:click="setoranDetail('{{ encrypt($stb->id) }}')"
                                            data-bs-target="#wm-detail-setoran-id">
                                            <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                                    style="font-size:13px"></i>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="w-row-title">{{ ucfirst($stb->penyetor->name) }} —
                                                    {{ number_format($stb->total_berat, 0, ',', '.') }} kg</div>
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
                <div wire:loading.flex wire:target="setoranDetail" class="justify-content-center align-items-center"
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
                            <div style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
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
                                        <td>{{ number_format($di['berat'], 0, ',', '.') }} KG</td>
                                        <td>Rp. {{ number_format($di['sub_total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Total</strong></td>
                                    <td>
                                        <strong>
                                            {{ number_format($itemSetoranDetail['total_berat'] ?? 0, 0, ',', '.') }}
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
    <div wire:ignore.self class="modal fade" id="wm-bongkar-gudang" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Buat Transaksi Gudang - {{ convertBeratToString($stokGudang) ?? 0 }}
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="doTrxGudang">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="col-12">
                                <label class="w-form-label">Total Berat</label>
                                <div class="position-relative">
                                    <input class="w-form-input pe-5" type="number" wire:model="totalBerat"
                                        placeholder="ex: 10">
                                    <span
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">kg</span>
                                </div>
                                @error('totalBerat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12" x-data="{
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
                                <label class="w-form-label">Nilai Rupiah</label>
                                <input class="w-form-input" type="text" :value="display"
                                    @input="update($event)" placeholder="ex: Rp 50.000">
                                @error('totalNilai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label class="w-form-label">Ketrangan</label>
                                <textarea class="w-form-input" wire:model="keterangan" rows="3" cols="3"
                                    placeholder="ex: Bongkar gudang untuk keperluan ...">
                                </textarea>
                                @error('totalNilai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                        </div>
                    </div>
                </form>
                <div class="w-modal-footer">
                    <button wire:click="doTrxGudang" wire:loading.attr="disabled" class="w-btn w-btn-primary"
                        style="width:auto;padding:7px 16px">
                        <span wire:loading.remove wire:target="doTrxGudang">
                            <i class="bi bi-check2-circle me-1"></i> Buat Transaksi
                        </span>
                        <span wire:loading wire:target="doTrxGudang">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="wm-detail-trx" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Transaksi - {{ $itemTrx->kode ?? 'TRX-XXX-XXX-XXX' }}
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div wire:loading.flex wire:target="trxDetail" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>

                @if ($itemTrx)
                    <div class="w-modal-body">
                        <div
                            style="background:var(--cyan-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                            <div
                                style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Nilai Transaksi</div>
                            <div
                                style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
                                Rp
                                {{ number_format($itemTrx->total_penarikan, 0, ',', '.') ?? 0 }}
                            </div>
                            <div
                                style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--cyan)">
                                {{ number_format($itemTrx->total_berat, 0, ',', '.') ?? 0 }} Kg
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">

                                <div class="detail-field"><span class="df-key">Petugas</span><span class="df-val">
                                        {{ ucfirst($itemTrx->admin->name) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                                        {{ $itemTrx->created_at->format('Y-m-d') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
