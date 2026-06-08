<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- We must ship. - Taylor Otwell --}}
    <div id="m-setoran">
        <div class="m-page-header">
            <div class="m-back" onclick="mNav('m-beranda')"><i class="bi bi-chevron-left" style="font-size:12px"></i></div>
            <div class="ph-title">Daftar Setoran</div>
            <div class="ms-auto d-flex gap-2">
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)">
                    <i class="bi bi-funnel-fill"></i>
                </div>
                <div class="m-gear"
                    style="font-size:14px;background:var(--cyan-10);border:1px solid var(--border);color:var(--cyan)"
                    onclick="openDetail('m-tambah-setoran')"><i class="bi bi-plus-lg"></i></div>
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
        <nav class="m-bottom-nav">
            <a class="m-nav-btn" data-page="m-beranda"><i class="bi bi-house-fill"></i><span>Beranda</span></a>
            <a class="m-nav-btn active" data-page="m-setoran"><i class="bi bi-recycle"></i><span>Setoran</span></a>
            <a class="m-nav-btn" data-page="m-nasabah"><i class="bi bi-people-fill"></i><span>Nasabah</span></a>
            <a class="m-nav-btn" data-page="m-profil"><i class="bi bi-person-fill"></i><span>Profil</span></a>
        </nav>
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
                            onclick="openWModal('wm-tambah-setoran')"><i class="bi bi-plus-lg me-1"></i>Catat
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
                                <th>Jenis Sampah</th>
                                <th>Berat</th>
                                <th>Nilai</th>
                                <th>Unit</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4712</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">Siti Rahayu</div>
                                </td>
                                <td><span class="bs bs-ok">Plastik HDPE</span></td>
                                <td style="font-weight:600">12,5 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp62.500</td>
                                <td style="font-size:10px;color:var(--muted)">Sukajadi</td>
                                <td style="font-size:10px;color:var(--muted)">15 mnt lalu</td>
                                <td><span class="bs bs-green">Lunas</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Detail</button></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4711</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">Hendra Wijaya</div>
                                </td>
                                <td><span class="bs bs-new">Kertas</span></td>
                                <td style="font-weight:600">8 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp24.000</td>
                                <td style="font-size:10px;color:var(--muted)">Tampan</td>
                                <td style="font-size:10px;color:var(--muted)">30 mnt lalu</td>
                                <td><span class="bs bs-green">Lunas</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Detail</button></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4710</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">CV Maju Bersama</div>
                                </td>
                                <td><span class="bs bs-purple">Logam</span></td>
                                <td style="font-weight:600">45 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp270.000</td>
                                <td style="font-size:10px;color:var(--muted)">Payung Sekaki</td>
                                <td style="font-size:10px;color:var(--muted)">1 jam lalu</td>
                                <td><span class="bs bs-warn">Pending</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Verifikasi</button></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4709</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">Dewi Kartika</div>
                                </td>
                                <td><span class="bs bs-ok">Kaca</span></td>
                                <td style="font-weight:600">5,2 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp15.600</td>
                                <td style="font-size:10px;color:var(--muted)">Marpoyan</td>
                                <td style="font-size:10px;color:var(--muted)">2 jam lalu</td>
                                <td><span class="bs bs-new">Ditimbang</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Detail</button></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4708</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">Agus Santoso</div>
                                </td>
                                <td><span class="bs bs-orange">Elektronik</span></td>
                                <td style="font-weight:600">3,1 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp62.000</td>
                                <td style="font-size:10px;color:var(--muted)">Bukit Raya</td>
                                <td style="font-size:10px;color:var(--muted)">3 jam lalu</td>
                                <td><span class="bs bs-green">Lunas</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Detail</button></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;color:var(--muted)">4707</td>
                                <td>
                                    <div style="font-size:11px;font-weight:600">Yuni Pratiwi</div>
                                </td>
                                <td><span class="bs bs-ok">Plastik PET</span></td>
                                <td style="font-weight:600">9 kg</td>
                                <td style="font-weight:700;color:var(--cyan)">Rp54.000</td>
                                <td style="font-size:10px;color:var(--muted)">Sail</td>
                                <td style="font-size:10px;color:var(--muted)">4 jam lalu</td>
                                <td><span class="bs bs-green">Lunas</span></td>
                                <td><button class="w-btn w-btn-ghost" style="font-size:10px;padding:4px 10px"
                                        onclick="openWModal('wm-detail-setoran')">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
