<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}
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
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('setoran') }}">
                        <div class="svc-icon ic1"><i class="bi bi-recycle"></i>
                            <div class="notif-dot">5</div>
                        </div><span class="svc-lbl">Setoran</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('nasabah') }}">
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
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}" >
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
</div>
