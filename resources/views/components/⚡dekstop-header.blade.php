<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    {{-- We must ship. - Taylor Otwell --}}
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
                    <div class="w-uname">{{ucfirst(Auth::user()->name)}}</div>
                    <div class="w-urole">{{ Auth::user()->unit->nama }}</div>
                </div>
                <div class="avatar avatar-sm">BS</div>
            </div>
        </div>
    </header>
</div>
