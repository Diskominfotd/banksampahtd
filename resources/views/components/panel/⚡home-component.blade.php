<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
    <div id="m-beranda">
        <div class="m-header">
            <div class="m-topbar">
                <div class="d-flex align-items-center gap-2" style="position:relative;z-index:2">
                    <div class="avatar avatar-md">BS</div>
                    <div>
                        <div style="font-size:10px;color:rgba(255,255,255,.70);margin-bottom:1px">Selamat datang</div>
                        <div style="font-size:14px;font-weight:600;color:#fff">Bank Sampah Nusantara</div>
                    </div>
                </div>
                <div class="m-gear" onclick="mNav('m-notifikasi')"><i class="bi bi-bell-fill"></i><span
                        style="position:absolute;top:-3px;right:-3px;width:14px;height:14px;border-radius:50%;background:var(--red);border:2px solid rgba(255,255,255,.4);font-size:7px;display:flex;align-items:center;justify-content:center;font-weight:700">3</span>
                </div>
            </div>
            <div class="m-summary fade-up">
                <div class="m-summary-lbl">Total Sampah Hari Ini</div>
                <div class="m-summary-num">342 kg</div>
                <div class="m-pills">
                    <div class="m-pill c"><span class="m-pill-n">128</span><span class="m-pill-l">Nasabah</span></div>
                    <div class="m-pill c"><span class="m-pill-n">47</span><span class="m-pill-l">Setoran</span></div>
                    <div class="m-pill"><span class="m-pill-n">Rp1,8J</span><span class="m-pill-l">Nilai</span></div>
                    <div class="m-pill"><span class="m-pill-n">5</span><span class="m-pill-l">Pending</span></div>
                </div>
            </div>
        </div>
        <div class="m-body">
            <div class="sec-lbl">Menu Utama</div>
            <div class="row g-2">
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-setoran')">
                        <div class="svc-icon ic1"><i class="bi bi-recycle"></i>
                            <div class="notif-dot">5</div>
                        </div><span class="svc-lbl">Setoran</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-nasabah')">
                        <div class="svc-icon ic2"><i class="bi bi-people-fill"></i></div><span
                            class="svc-lbl">Nasabah</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-grafik')">
                        <div class="svc-icon ic3"><i class="bi bi-graph-up-arrow"></i></div><span
                            class="svc-lbl">Laporan</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-profil')">
                        <div class="svc-icon ic4"><i class="bi bi-person-fill"></i></div><span
                            class="svc-lbl">Profil</span>
                    </a></div>
            </div>
            <div class="row g-2 mt-1">
                <div class="col-3 fade-up"><a class="svc-item" onclick="openDetail('m-tambah-setoran')">
                        <div class="svc-icon ic5"><i class="bi bi-plus-circle-fill"></i></div><span
                            class="svc-lbl">Catat Baru</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-harga')">
                        <div class="svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                            class="svc-lbl">Harga</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-setoran')">
                        <div class="svc-icon ic6"><i class="bi bi-clock-history"></i></div><span
                            class="svc-lbl">Riwayat</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-penarikan')">
                        <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span class="svc-lbl">Saldo</span>
                    </a></div>
            </div>

            <div class="sec-lbl">Setoran Terbaru</div>
            <div class="d-flex flex-column gap-3">
                <!-- kartu setoran besar -->
                <div class="news-card fade-up" onclick="openDetail('m-detail-setoran')">
                    <div class="news-img"><i class="bi bi-recycle"></i></div>
                    <div class="news-body">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="bs bs-green">Diterima</span>
                            <span class="bs bs-ok">Plastik</span>
                        </div>
                        <div class="news-title">Ibu Siti Rahayu — 12,5 kg Plastik HDPE</div>
                        <div class="news-excerpt">Setoran rutin mingguan oleh nasabah aktif. Terdiri dari botol
                            plastik, kemasan, dan kantong belanja yang telah dipilah dengan baik.</div>
                        <div class="news-meta mt-2"><i class="bi bi-clock me-1"></i>15 menit lalu · Unit Sukajadi ·
                            Rp62.500</div>
                    </div>
                </div>

                <div class="tx-card d-flex align-items-start gap-2 fade-up" onclick="openDetail('m-detail-setoran')">
                    <div class="tx-ico" style="background:rgba(27,94,32,.10);color:var(--blue)"><i
                            class="bi bi-newspaper" style="font-size:14px"></i></div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="tx-name text-truncate">Bpk. Hendra Wijaya — 8 kg Kertas</div>
                        <div class="tx-date"><i class="bi bi-clock me-1"></i>30 menit lalu · Unit Tampan · Rp24.000
                        </div>
                        <div class="d-flex gap-1 mt-2">
                            <button class="btn-tx"
                                onclick="event.stopPropagation();openDetail('m-detail-setoran')">Detail</button>
                            <button class="btn-tx">Cetak</button>
                        </div>
                    </div>
                    <span class="bs bs-green flex-shrink-0">Lunas</span>
                </div>

                <div class="tx-card d-flex align-items-start gap-2 fade-up" onclick="openDetail('m-detail-setoran')">
                    <div class="tx-ico" style="background:rgba(92,53,168,.10);color:var(--purple)"><i
                            class="bi bi-cpu-fill" style="font-size:14px"></i></div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="tx-name text-truncate">CV Maju Bersama — 45 kg Logam Campuran</div>
                        <div class="tx-date"><i class="bi bi-clock me-1"></i>1 jam lalu · Unit Payung Sekaki ·
                            Rp270.000</div>
                        <div class="d-flex gap-1 mt-2">
                            <button class="btn-tx"
                                onclick="event.stopPropagation();openDetail('m-detail-setoran')">Detail</button>
                            <button class="btn-tx red">Verifikasi</button>
                        </div>
                    </div>
                    <span class="bs bs-warn flex-shrink-0">Pending</span>
                </div>

                <div class="tx-card d-flex align-items-start gap-2 fade-up" onclick="openDetail('m-detail-setoran')">
                    <div class="tx-ico" style="background:rgba(0,121,107,.10);color:var(--teal)"><i
                            class="bi bi-box-fill" style="font-size:14px"></i></div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="tx-name text-truncate">Ibu Dewi Kartika — 5,2 kg Kaca</div>
                        <div class="tx-date"><i class="bi bi-clock me-1"></i>2 jam lalu · Unit Marpoyan · Rp15.600
                        </div>
                        <div class="d-flex gap-1 mt-2">
                            <button class="btn-tx">Detail</button>
                            <button class="btn-tx">Cetak</button>
                        </div>
                    </div>
                    <span class="bs bs-new flex-shrink-0">Ditimbang</span>
                </div>
            </div>
        </div>
        @include('panel.template.mobile-bottombar')
    </div>
    <div class="desktop-wrapper">
         @include('components.⚡dekstop-navbar')
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
                            <div class="w-urole">{{Auth::user()->unit->nama}}</div>
                        </div>
                        <div class="avatar avatar-sm">BS</div>
                    </div>
                </div>
            </header>
            <div id="w-dashboard" class="w-content">
                <div class="row g-3">
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Total Sampah Hari Ini</div>
                            <div class="w-m-val" style="color:var(--cyan)">342 kg</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+18% kemarin</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:78%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nilai Setoran</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp1,8 Jt</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+12% kemarin</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:65%;background:var(--blue)"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Nasabah Aktif</div>
                            <div class="w-m-val">128</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+3 nasabah baru</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:82%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Setoran Pending</div>
                            <div class="w-m-val">5</div>
                            <div class="w-m-delta" style="color:var(--yellow)"><i
                                    class="bi bi-exclamation-circle"></i>Perlu verifikasi</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:12%;background:var(--yellow)"></div>
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
                            <div class="w-panel-title mt-3">Komposisi Sampah</div>
                            <div class="d-flex flex-column gap-2">
                                <div
                                    style="background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;padding:8px 12px">
                                    <div class="d-flex justify-content-between align-items-center mb-1"><span
                                            style="font-size:11px;font-weight:600">Plastik</span><span
                                            style="font-size:10px;color:var(--cyan);font-weight:700">42% · 143
                                            kg</span></div>
                                    <div class="prog-wrap">
                                        <div class="prog-fill" style="width:42%;background:var(--cyan)"></div>
                                    </div>
                                </div>
                                <div
                                    style="background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;padding:8px 12px">
                                    <div class="d-flex justify-content-between align-items-center mb-1"><span
                                            style="font-size:11px;font-weight:600">Kertas</span><span
                                            style="font-size:10px;color:var(--blue);font-weight:700">28% · 96
                                            kg</span></div>
                                    <div class="prog-wrap">
                                        <div class="prog-fill" style="width:28%;background:var(--blue)"></div>
                                    </div>
                                </div>
                                <div
                                    style="background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;padding:8px 12px">
                                    <div class="d-flex justify-content-between align-items-center mb-1"><span
                                            style="font-size:11px;font-weight:600">Logam</span><span
                                            style="font-size:10px;color:var(--purple);font-weight:700">18% · 62
                                            kg</span></div>
                                    <div class="prog-wrap">
                                        <div class="prog-fill" style="width:18%;background:var(--purple)"></div>
                                    </div>
                                </div>
                                <div
                                    style="background:var(--bg-deep);border:1px solid var(--border);border-radius:10px;padding:8px 12px">
                                    <div class="d-flex justify-content-between align-items-center mb-1"><span
                                            style="font-size:11px;font-weight:600">Elektronik</span><span
                                            style="font-size:10px;color:var(--orange);font-weight:700">7% · 24
                                            kg</span></div>
                                    <div class="prog-wrap">
                                        <div class="prog-fill" style="width:7%;background:var(--orange)"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="w-panel h-100">
                            <div class="w-panel-title">Setoran Masuk Terbaru</div>
                            <div class="d-flex flex-column gap-2">
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic1"><i class="bi bi-recycle" style="font-size:13px"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Siti Rahayu — 12,5 kg Plastik HDPE</div>
                                        <div class="w-row-meta">Unit Sukajadi · 15 mnt lalu · Rp62.500</div>
                                    </div><span class="bs bs-green">Lunas</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic2"><i class="bi bi-newspaper" style="font-size:13px"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Hendra Wijaya — 8 kg Kertas</div>
                                        <div class="w-row-meta">Unit Tampan · 30 mnt lalu · Rp24.000</div>
                                    </div><span class="bs bs-green">Lunas</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic4"><i class="bi bi-cpu-fill" style="font-size:13px"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">CV Maju Bersama — 45 kg Logam Campuran</div>
                                        <div class="w-row-meta">Unit Payung Sekaki · 1 jam lalu · Rp270.000</div>
                                    </div><span class="bs bs-warn">Pending</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic5"><i class="bi bi-box-fill" style="font-size:13px"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Dewi Kartika — 5,2 kg Kaca</div>
                                        <div class="w-row-meta">Unit Marpoyan · 2 jam lalu · Rp15.600</div>
                                    </div><span class="bs bs-new">Ditimbang</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic3"><i class="bi bi-lightning-fill"
                                            style="font-size:13px"></i></div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Agus Santoso — 3,1 kg Elektronik</div>
                                        <div class="w-row-meta">Unit Bukit Raya · 3 jam lalu · Rp62.000</div>
                                    </div><span class="bs bs-ok">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
