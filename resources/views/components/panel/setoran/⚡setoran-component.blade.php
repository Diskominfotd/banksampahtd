<?php

use Livewire\Component;
use App\Services\SetoranService;
use Livewire\WithPagination;
new class extends Component {
    use WithPagination;
    protected SetoranService $setoranService;

    public array $detailItems = [];

    public function boot(SetoranService $setoranService)
    {
        $this->setoranService = $setoranService;
    }
    public function movePage(string $route)
    {
        return redirect()->route($route);
    }
    public function detailSetoran(string $nasabahId)
    {
        $nasabahId = decrypt($nasabahId);
        $item = $this->setoranService->getSetoranByIdNasabah($nasabahId);
        $this->detailItems = $item->toArray();
        // dd(json_encode( $this->detailItems, JSON_PRETTY_PRINT));
        // dd($this->detailItems);
    }

    public function getData()
    {
        $builder = $this->setoranService->getSetoranByUnit();
        // dd(json_encode($builder->latest()->paginate(10), JSON_PRETTY_PRINT));
        return [
            'setoran' => $builder->latest()->paginate(10),
        ];
    }
};
?>

<div>
    {{-- We must ship. - Taylor Otwell --}}
    @php
        $data = $this->getData();
    @endphp
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" wire:click="movePage('home')"><i class="bi bi-chevron-left" style="font-size:12px"></i>
            </div>
            <div class="ph-title">Daftar Setoran</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    wire:click="movePage('setoran.catat')"><i class="bi bi-plus-lg"></i></div>
            </div>
        </div>
        <div class="m-body" style="padding-top:16px">
            <div class="m-search mb-2"><i class="bi bi-search si"></i><input type="text"
                    placeholder="Cari nasabah, jenis sampah..."></div>
            <div class="m-chips mb-3">
                <button class="chip active">Semua</button>
                <button class="chip">Plastik</button>
                <button class="chip">Kertas</button>
                <button class="chip">Logam</button>
                <button class="chip">Kaca</button>
                <button class="chip">Elektronik</button>
            </div>
            <div class="d-flex flex-column gap-2">
                <div class="list-item fade-up"><span class="list-num">1</span>
                    <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">Siti Rahayu — 12,5 kg Plastik</div>
                        <div class="list-sub">Unit Sukajadi · 15 mnt lalu · Rp62.500</div>
                    </div><span class="bs bs-green" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Lunas</span>
                </div>
                <div class="list-item fade-up"><span class="list-num">2</span>
                    <div class="list-ico ic2"><i class="bi bi-newspaper" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">Hendra Wijaya — 8 kg Kertas</div>
                        <div class="list-sub">Unit Tampan · 30 mnt lalu · Rp24.000</div>
                    </div><span class="bs bs-green" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Lunas</span>
                </div>
                <div class="list-item fade-up"><span class="list-num">3</span>
                    <div class="list-ico ic4"><i class="bi bi-cpu-fill" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">CV Maju Bersama — 45 kg Logam</div>
                        <div class="list-sub">Unit Payung Sekaki · 1 jam lalu · Rp270.000</div>
                    </div><span class="bs bs-warn" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Pending</span>
                </div>
                <div class="list-item fade-up"><span class="list-num">4</span>
                    <div class="list-ico ic5"><i class="bi bi-box-fill" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">Dewi Kartika — 5,2 kg Kaca</div>
                        <div class="list-sub">Unit Marpoyan · 2 jam lalu · Rp15.600</div>
                    </div><span class="bs bs-new" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Ditimbang</span>
                </div>
                <div class="list-item fade-up"><span class="list-num">5</span>
                    <div class="list-ico ic3"><i class="bi bi-lightning-fill" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">Agus Santoso — 3,1 kg Elektronik</div>
                        <div class="list-sub">Unit Bukit Raya · 3 jam lalu · Rp62.000</div>
                    </div><span class="bs bs-ok" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Lunas</span>
                </div>
                <div class="list-item fade-up"><span class="list-num">6</span>
                    <div class="list-ico ic1"><i class="bi bi-recycle" style="font-size:12px"></i></div>
                    <div class="list-main">
                        <div class="list-name">Yuni Pratiwi — 9 kg Plastik PET</div>
                        <div class="list-sub">Unit Sail · 4 jam lalu · Rp54.000</div>
                    </div><span class="bs bs-green" style="cursor:pointer"
                        onclick="openDetail('m-detail-setoran')">Lunas</span>
                </div>
            </div>
        </div>
        @include('panel.template.mobile-bottombar')
    </div>

    {{-- ======= DEKSTOP ======= --}}
    <div class="desktop-wrapper">
        @include('panel.template.dekstop-navbar')
        <div class="w-main">
            <header class="w-topbar">
                <div id="w-topbar-info">
                    <div class="w-title">Dashboard Pengelola</div>
                    <div class="w-sub">Jumat, 29 Mei 2026 · Bank Sampah Nusantara, Pekanbaru</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="w-search"><i class="bi bi-search si"></i><input type="text"
                            placeholder="Cari nasabah, setoran..."></div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="text-end">
                            <div class="w-uname">Budi Santoso</div>
                            <div class="w-urole">Pengelola Bank Sampah</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>
            <!-- W-PAGE: Setoran -->
            <div id="w-setoran" class="w-content">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <div style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700">Manajemen Setoran
                        </div>
                        <div style="font-size:11px;color:var(--muted)">342 kg masuk hari ini — 5 setoran pending</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="w-btn w-btn-ghost" style="font-size:11px"><i
                                class="bi bi-download me-1"></i>Export</button>
                        <button class="w-btn w-btn-ghost" style="font-size:11px" onclick="openWModal('wm-filter')"><i
                                class="bi bi-funnel me-1"></i>Filter</button>
                        <button class="w-btn w-btn-primary" style="font-size:11px"
                            wire:click="movePage('setoran.catat')"><i class="bi bi-plus-lg me-1"></i>Catat
                            Setoran</button>
                    </div>
                </div>
                <div class="w-panel">
                    <div class="d-flex gap-2 mb-3">
                        <div class="w-search flex-grow-1"><i class="bi bi-search si"></i><input type="text"
                                placeholder="Cari nasabah, jenis sampah..." style="width:100%"></div>
                        <div class="d-flex gap-1">
                            <button class="chip active">Semua</button>
                            <button class="chip">Plastik</button>
                            <button class="chip">Kertas</button>
                            <button class="chip">Logam</button>
                            <button class="chip">Elektronik</button>
                        </div>
                    </div>
                    <table class="w-tbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nasabah</th>
                                <th>Nomor Rekening</th>
                                <th>Berat</th>
                                <th>Nilai</th>
                                <th>Unit Setor</th>
                                <th>Waktu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['setoran'] as $st)
                                <tr>
                                    <td style="font-size:10px;color:var(--muted)">1</td>
                                    <td>
                                        <div style="font-size:11px;font-weight:600">{{ ucfirst($st->penyetor->name) }}
                                        </div>
                                    </td>
                                    <td><span class="bs bs-ok">
                                            @foreach ($st->penyetor->bukutabungans as $buku)
                                                {{ $buku->nomor_rekening }}
                                            @endforeach
                                        </span>
                                    </td>
                                    <td style="font-weight:600"> {{ number_format($st->total_berat, 0, ',', '.') }} kg
                                    </td>
                                    <td style="font-weight:700;color:var(--cyan)">Rp
                                        {{ number_format($st->total_saldo, 0, ',', '.') }}
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        @foreach ($st->penyetor->bukutabungans as $buku)
                                            {{ $buku->bank->nama }}
                                        @endforeach
                                    </td>
                                    <td style="font-size:10px;color:var(--muted)">
                                        {{ $st->created_at->timezone('Asia/Jakarta')->diffForHumans() }}
                                    </td>
                                    <td>
                                        <button wire:click="detailSetoran('{{ encrypt($st->penyetor->id) }}')"
                                            class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                            data-bs-toggle="modal" data-bs-target="#wm-detail-setoran">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- ======= MODAL DESKTOP: DETAIL Setoran ======= --}}
    <div wire:ignore.self class="modal fade" id="wm-detail-setoran" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content w-modal">
                <div class="w-modal-header">
                    <div class="w-modal-title">Detail Setoran</div>
                    <div class="w-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></div>
                </div>
                <div class="w-modal-body">
                    <div wire:loading.flex wire:target="detail" class="justify-content-center align-items-center"
                        style="position:absolute;inset:0;background:rgba(255,255,255,0.6);z-index:10;border-radius:inherit">
                        <div class="spinner-border text-success"></div>
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
                            @foreach ($detailItems['items'] ?? [] as $index => $di)
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
                                        {{ number_format($detailItems['total_berat'] ?? 0, 0, ',', '.') }}
                                        KG
                                    </strong>
                                </td>
                                <td>
                                    <strong>
                                        Rp. {{ number_format($detailItems['total_saldo'] ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="w-modal-footer">
                    {{-- <button class="w-btn w-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                    <button class="w-btn w-btn-ghost" data-bs-dismiss="modal"
                        onclick="setTimeout(()=> new bootstrap.Modal(document.getElementById('wm-tambah-setoran')).show(), 300)">
                        Catat Setoran
                    </button>
                    <button class="w-btn w-btn-danger">Proses Penarikan</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
