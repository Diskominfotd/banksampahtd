<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk --}}
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
                                    <div class="w-row-ico ic1"><i class="bi bi-recycle"
                                            style="font-size:13px"></i></div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Siti Rahayu — 12,5 kg Plastik HDPE</div>
                                        <div class="w-row-meta">Unit Sukajadi · 15 mnt lalu · Rp62.500</div>
                                    </div><span class="bs bs-green">Lunas</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic2"><i class="bi bi-newspaper"
                                            style="font-size:13px"></i></div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">Hendra Wijaya — 8 kg Kertas</div>
                                        <div class="w-row-meta">Unit Tampan · 30 mnt lalu · Rp24.000</div>
                                    </div><span class="bs bs-green">Lunas</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic4"><i class="bi bi-cpu-fill"
                                            style="font-size:13px"></i></div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="w-row-title">CV Maju Bersama — 45 kg Logam Campuran</div>
                                        <div class="w-row-meta">Unit Payung Sekaki · 1 jam lalu · Rp270.000</div>
                                    </div><span class="bs bs-warn">Pending</span>
                                </div>
                                <div class="w-row" onclick="openWModal('wm-detail-setoran')">
                                    <div class="w-row-ico ic5"><i class="bi bi-box-fill"
                                            style="font-size:13px"></i></div>
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