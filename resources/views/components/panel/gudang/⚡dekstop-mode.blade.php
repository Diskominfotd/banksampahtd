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
                            <div class="w-m-lbl">Total Sampah</div>
                            <div class="w-m-val" style="color:var(--cyan)">342 kg</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+18% kemarin</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:78%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Pendapatan</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp1,8 Jt</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+12% kemarin</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:65%;background:var(--blue)"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 fade-up">
                        <div class="w-metric">
                            <div class="w-m-lbl">Pengeluaran</div>
                            <div class="w-m-val" style="color:var(--blue)">Rp1,8 Jt</div>
                            <div class="w-m-delta up"><i class="bi bi-arrow-up-short"></i>+12% kemarin</div>
                            <div class="w-bar">
                                <div class="w-bar-fill" style="width:65%;background:var(--blue)"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="w-panel h-100">
                            <div class="w-panel-title">Bongkar Setoran</div>
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
                    <div class="col-6">
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
