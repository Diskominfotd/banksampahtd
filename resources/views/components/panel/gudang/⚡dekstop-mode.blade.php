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
                            <div class="w-m-lbl">Total Kas Masuk
                                <small class="d-block text-muted" style="font-size: 7px; font-style: italic;">
                                    Total penjualan setoran nasabah, donasi dll
                                </small>
                            </div>
                            <div class="w-m-val" style="color:var(--cyan)">
                                Rp {{ number_format($data['totalPendapatan']['total'], 0, ',', '.') }}
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
                            <div class="w-m-lbl">Total Kas Keluar
                                <small class="d-block text-muted" style="font-size: 7px; font-style: italic;">
                                    (Total pengeluaran oprasional,penarikan tabungan nasabah)
                                </small>
                            </div>
                            <div class="w-m-val" style="color:var(--blue)">
                                Rp {{ number_format($data['totalPenarikanSaldoNasabah']['total'], 0, ',', '.') }}
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
                            <div class="w-m-lbl">Sisa Kas (Kas Tersedia)
                                <small class="d-block text-muted" style="font-size: 7px; font-style: italic;">
                                    (Total kas Masuk - Total kas Keluar)
                                </small>
                            </div>
                            <div class="w-m-val" style="color:var(--blue)">
                                Rp {{ number_format($data['pendapatanbersih']['total'], 0, ',', '.') }}
                            </div>
                            @php
                                $persentase = $data['pendapatanbersih']['persentase'];
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
                                $persen = $data['pendapatanbersih']['persentase'];
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
                            <div class="w-m-lbl">Total Tabungan Nasabah
                                <small class="d-block text-muted" style="font-size: 7px; font-style: italic;">
                                    (Total Tabungan Nasabah yang belum ditarik)
                                </small>
                            </div>
                            <div class="w-m-val" style="color:var(--blue)">
                                Rp {{ number_format($data['totalSetoran']['total'], 0, ',', '.') }}
                            </div>
                            @php
                                $persentase = $data['totalSetoran']['persentase'];
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
                                $persen = $data['totalSetoran']['persentase'];
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
                            <div class="w-m-lbl">Keuntungan
                                <small class="d-block text-muted" style="font-size: 7px; font-style: italic;">
                                    (Sisa Kas – Tabungan Nasabah yang belum ditarik)
                                </small>
                            </div>
                              <div class="w-m-val" style="color:var(--blue)">
                                Rp {{ number_format($data['keuntungan']['total'], 0, ',', '.') }}
                            </div>
                            @php
                                $persentase = $data['keuntungan']['persentase'];
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
                                $persen = $data['keuntungan']['persentase'];
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
                    <div class="col-6">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="w-panel-title">Transaksi Kas Masuk
                                    <small class="d-block text-muted" style="font-size: 9px; font-style: italic;">
                                        Penjualan kepada Pihak Ketiga, Donasi, Pendapatan Lain-Lain
                                    </small>
                                </div>
                                <span data-bs-toggle="modal" data-bs-target="#wm-bongkar-gudang" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">
                                    <i class="bi bi-box-arrow-up"></i> Buat Transaksi
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
                                            </div>
                                        </div>
                                        <span class="bs bs-new">
                                            Rp{{ number_format($tb->total_penarikan, 0, ',', '.') }}
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
                                {{ $data['trx']->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="w-panel h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="w-panel-title">Transaksi Kas Keluar
                                    <small class="d-block text-muted" style="font-size: 9px; font-style: italic;">
                                        Operasional+Tabungan yang ditarik
                                    </small>
                                </div>
                                <span data-bs-toggle="modal" data-bs-target="#wm-trx-pengeluaran" class="bs bs-green"
                                    style="font-size: 12px; cursor: pointer;">
                                    <i class="bi bi-box-arrow-up"></i> Buat Transaksi
                                </span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if ($data['pengeluaran']->isNotEmpty())
                                    @foreach ($data['pengeluaran'] as $pg)
                                        <div class="w-row" data-bs-toggle="modal"
                                            data-bs-target="#wm-detail-pengeluaran"
                                            wire:click="trxDetailPengeluaran('{{ encrypt($pg->id) }}')">
                                            <div class="w-row-ico ic5"><i class="bi bi-dash-circle"
                                                    style="font-size:13px"></i>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="w-row-title">{{ $pg->kode ?? '-' }}</div>
                                                <div class="w-row-meta"><b>{{ ucfirst($pg->admin->name) }}</b> ·
                                                    {{ $pg->created_at?->diffForHumans() }}
                                                </div>
                                            </div>
                                            <span class="bs bs-err">
                                                - Rp{{ number_format($pg->total_penarikan, 0, ',', '.') }}
                                            </span>
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
                            <div class="mt-2">
                                {{ $data['pengeluaran']->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Dekstop : Buat Pemasukan --}}
    <div wire:ignore.self class="modal fade" id="wm-bongkar-gudang" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Buat Transaksi Gudang
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="doTrxGudang">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
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
                                    placeholder="ex: Penjualan sampah ke....">
                                </textarea>
                                @error('keterangan')
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
    {{-- Modal Dekstop : Detail Pemasukan --}}
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
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="detail-field"><span class="df-key">Petugas</span><span class="df-val">
                                        {{ ucfirst($itemTrx->admin->name) }}
                                    </span>
                                </div>
                                <div class="detail-field"><span class="df-key">Keterangan</span><span class="df-val">
                                        <p>{{ ucfirst($itemTrx->keterangan ?? '-') }}</p>
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
                    <div class="w-modal-footer">
                        <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                        <button wire:click="trxDetailEdit('{{ encrypt($itemTrx->id) }}')" class="w-btn w-btn-primary"
                            data-bs-toggle="modal" data-bs-target="#wm-edit-trx">Edit</button>
                        <button
                            x-on:click="Swal.fire({
                        title: 'Hapus Transaksi Ini ?',
                        html: '<span style=\'color:#6b7280;font-size:14px\'>Data yang dihapus <b>tidak bisa dikembalikan</b>. Pastikan Anda yakin sebelum melanjutkan.</span>',
                        icon: 'warning',
                        iconColor: '#d33',
                        showCancelButton: true,
                        confirmButtonText: '<i class=\'bi bi-trash3 me-1\'></i> Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        focusCancel: true,
                        buttonsStyling: true,
                        customClass: {
                        popup: 'rounded-4 shadow-lg',
                        title: 'fw-bold fs-5',
                        confirmButton: 'px-4 py-2 rounded-3',
                        cancelButton: 'px-4 py-2 rounded-3'
                        },
                        showClass: {
                        popup: 'animate__animated animate__zoomIn animate__faster'
                        },
                        hideClass: {
                        popup: 'animate__animated animate__zoomOut animate__faster'
                        }
                        }).then((result) => {
                        if (result.isConfirmed) {
                        Livewire.dispatch('doDeleteTrxGudang', { trxId: '{{ encrypt($itemTrx->id) }}' })
                        }
                        })"class="w-btn w-btn-danger">Hapus
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Modal Dekstop : Edit Pemasukan --}}
    <div wire:ignore.self class="modal fade" id="wm-edit-trx" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">
                        Edit Transaksi Gudang {{ $kodeTrx ?? 'TRX-XXX-XXX-XXX' }}
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div wire:loading.flex wire:target="trxDetailEdit" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <form wire:submit="editTrxGudang">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="col-12" x-data="{
                                nilai: @entangle('nilaiTrx'),
                                display: '',
                                init() {
                                    this.display = this.format(this.nilai ?? 0);
                            
                                    this.$watch('nilai', (value) => {
                                        this.display = this.format(value ?? 0);
                                    });
                                },
                                format(val) {
                                    let num = String(val).replace(/\D/g, '');
                                    if (!num) num = '0';
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                                },
                                update(e) {
                                    let raw = e.target.value.replace(/\D/g, '');
                                    this.nilai = raw;
                                    this.display = this.format(raw);
                                }
                            }">
                                <label class="w-form-label">Nilai Rupiah</label>
                                <input class="w-form-input" type="text" :value="display"
                                    @input="update($event)" placeholder="ex: Rp 50.000">
                                @error('nilaiTrx')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label class="w-form-label">Ketrangan</label>
                                <textarea class="w-form-input" wire:model="keteranganTrx" rows="3" cols="3"
                                    placeholder="ex: Penjualan sampah ke....">
                                </textarea>
                                @error('keteranganTrx')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>

                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editTrxGudang">
                            <span wire:loading.remove wire:target="editTrxGudang">
                                Simpan
                            </span>
                            <span wire:loading wire:target="editTrxGudang">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal Dekstop : Buat Pengeluaran --}}
    <div wire:ignore.self class="modal fade" id="wm-trx-pengeluaran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-m">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Buat Transaksi Pengeluaran
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <form wire:submit="addPengeluaran">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="col-12" x-data="{
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
                                <label class="w-form-label">Nilai Rupiah</label>
                                <input class="w-form-input" type="text" :value="display"
                                    @input="update($event)" placeholder="ex: Rp 50.000">
                                @error('totalNilaiPengeluaran')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label class="w-form-label">Ketrangan</label>
                                <textarea class="w-form-input" wire:model="keteranganPengeluaran" rows="3" cols="3"
                                    placeholder="ex: Bayar rambahan dan PLN....">
                                </textarea>
                                @error('keteranganPengeluaran')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
                <div class="w-modal-footer">
                    <button wire:click="addPengeluaran" wire:loading.attr="disabled" class="w-btn w-btn-primary"
                        style="width:auto;padding:7px 16px">
                        <span wire:loading.remove wire:target="addPengeluaran">
                            <i class="bi bi-check2-circle me-1"></i> Buat Transaksi
                        </span>
                        <span wire:loading wire:target="addPengeluaran">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Dekstop : Detail Pengeluaran --}}
    <div wire:ignore.self class="modal fade" id="wm-detail-pengeluaran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Transaksi - {{ $itemTrxPengeluaran->kode ?? 'PRN-XXX-XXX-XXX' }}
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div wire:loading.flex wire:target="trxDetailPengeluaran"
                    class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                @if ($itemTrxPengeluaran)
                    <div class="w-modal-body">
                        <div
                            style="background:var(--red-10);border:1px solid var(--cyan-bd);border-radius:14px;padding:18px;text-align:center;margin-bottom:20px">
                            <div
                                style="font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px">
                                Nilai Transaksi</div>
                            <div style="font-family:'Syne',sans-serif;font-size:36px;font-weight:700;color:var(--red)">
                                Rp
                                {{ number_format($itemTrxPengeluaran->total_penarikan, 0, ',', '.') ?? 0 }}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="detail-field"><span class="df-key">Petugas</span><span class="df-val">
                                        {{ ucfirst($itemTrxPengeluaran->admin->name) }}
                                    </span>
                                </div>
                                <div class="detail-field"><span class="df-key">Keterangan</span><span class="df-val">
                                        <p>{{ ucfirst($itemTrxPengeluaran->keterangan ?? '-') }}</p>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-field"><span class="df-key">Tanggal</span><span class="df-val">
                                        {{ $itemTrxPengeluaran->created_at->format('Y-m-d') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                        {{-- <button wire:click="trxPengeluranDetail('{{ encrypt($itemTrxPengeluaran->id) }}')"
                            class="w-btn w-btn-primary" data-bs-toggle="modal"
                            data-bs-target="#wm-edit-trx-pengeluaran">Edit
                        </button> --}}
                        <button
                            x-on:click="Swal.fire({
                            title: 'Hapus Transaksi Ini ?',
                            html: '<span style=\'color:#6b7280;font-size:14px\'>Data yang dihapus <b>tidak bisa dikembalikan</b>. Pastikan Anda yakin sebelum melanjutkan.</span>',
                            icon: 'warning',
                            iconColor: '#d33',
                            showCancelButton: true,
                            confirmButtonText: '<i class=\'bi bi-trash3 me-1\'></i> Ya, Hapus',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6b7280',
                            reverseButtons: true,
                            focusCancel: true,
                            buttonsStyling: true,
                            customClass: {
                            popup: 'rounded-4 shadow-lg',
                            title: 'fw-bold fs-5',
                            confirmButton: 'px-4 py-2 rounded-3',
                            cancelButton: 'px-4 py-2 rounded-3'
                            },
                            showClass: {
                            popup: 'animate__animated animate__zoomIn animate__faster'
                            },
                            hideClass: {
                            popup: 'animate__animated animate__zoomOut animate__faster'
                            }
                            }).then((result) => {
                            if (result.isConfirmed) {
                            Livewire.dispatch('doDeleteTrxPengeluaran', { trxId: '{{ encrypt($itemTrxPengeluaran->id) }}' })
                            }
                            })"class="w-btn w-btn-danger">Hapus
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Modal Dekstop : Edit Pengeluaran --}}
    <div wire:ignore.self class="modal fade" id="wm-edit-trx-pengeluaran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">
                        Edit Transaksi Pengeluaran {{ $kodeTrxPengeluaran ?? 'PRN-XXX-XXX-XXX' }}
                    </div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div wire:loading.flex wire:target="trxDetailEdit" class="justify-content-center align-items-center"
                    style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                    <div class="spinner-border text-success"></div>
                </div>
                <form wire:submit="editTrxPengeluaran">
                    <div class="w-modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="col-12" x-data="{
                                nilai: @entangle('nilaiTrxPengeluaran'),
                                display: '',
                                init() {
                                    this.display = this.format(this.nilai ?? 0);
                            
                                    this.$watch('nilai', (value) => {
                                        this.display = this.format(value ?? 0);
                                    });
                                },
                                format(val) {
                                    let num = String(val).replace(/\D/g, '');
                                    if (!num) num = '0';
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                                },
                                update(e) {
                                    let raw = e.target.value.replace(/\D/g, '');
                                    this.nilai = raw;
                                    this.display = this.format(raw);
                                }
                            }">
                                <label class="w-form-label">Nilai Rupiah</label>
                                <input class="w-form-input" type="text" :value="display"
                                    @input="update($event)" placeholder="ex: Rp 50.000">
                                @error('nilaiTrxPengeluaran')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <label class="w-form-label">Ketrangan</label>
                                <textarea class="w-form-input" wire:model="keteranganTrxPengeluaran" rows="3" cols="3"
                                    placeholder="ex: Penjualan sampah ke....">
                                </textarea>
                                @error('keteranganTrxPengeluaran')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-modal-footer">
                        <button type="button" class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>

                        <button type="submit" class="w-btn w-btn-primary" wire:loading.attr="disabled"
                            wire:target="editTrxGudang">
                            <span wire:loading.remove wire:target="editTrxGudang">
                                Simpan
                            </span>
                            <span wire:loading wire:target="editTrxGudang">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
