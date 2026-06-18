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
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                        <div class="svc-icon ic3"><i class="bi bi-tags-fill"></i></div><span
                            class="svc-lbl">Harga</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('kategori') }}">
                        <div class="svc-icon ic4"><i class="bi bi-grid-fill"></i></div><span
                            class="svc-lbl">Kategori</span>
                    </a></div>
            </div>
            <div class="row g-2 mt-1">
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('penarikan.saldo') }}">
                        <div class="svc-icon ic5"><i class="bi bi-cash-coin"></i></div><span
                            class="svc-lbl">Penarikan</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" href="{{ route('harga') }}">
                        <div class="svc-icon ic3"><i class="bi bi-graph-up"></i></div><span
                            class="svc-lbl">Laporan</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-setoran')">
                        <div class="svc-icon ic6"><i class="bi bi-person-circle"></i></div><span
                            class="svc-lbl">Profile</span>
                    </a></div>
                <div class="col-3 fade-up"><a class="svc-item" onclick="mNav('m-penarikan')">
                        <div class="svc-icon ic2"><i class="bi bi-wallet2"></i></div><span class="svc-lbl">Saldo</span>
                    </a></div>
            </div>
        </div>
        @include('panel.template.mobile-bottombar')
    </div>
</div>
