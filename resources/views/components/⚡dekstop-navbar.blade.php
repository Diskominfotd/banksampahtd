<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<style>
    .w-tooltip {
        position: relative;
    }

    .w-tooltip::after {
        content: attr(data-tip);
        position: absolute;
        left: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%);
        background: #1a1a1a;
        color: #fff;
        font-size: 12px;
        white-space: nowrap;
        padding: 4px 10px;
        border-radius: 6px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease;
        z-index: 9999;
    }

    .w-tooltip:hover::after {
        opacity: 1;
    }
</style>

<div>
    <aside class="w-sidebar" x-data="{ path: window.location.pathname }">

        <a href="/" class="w-nav w-tooltip" data-tip="Beranda" :class="{ 'active': path === '/' }">
            <i class="bi bi-house-fill"></i>
        </a>
        @if (Auth::user()->hasRole(['admin', 'supervisor']))
            <a href="/gudang" class="w-nav w-tooltip" data-tip="Gudang" :class="{ 'active': path.startsWith('/gudang') }">
                <i class="bi bi-trash3-fill"></i>
            </a>
            <a href="/penarikan" class="w-nav w-tooltip" data-tip="Beranda"
                :class="{ 'active': path.startsWith('/penarikan') }">
                <i class="bi bi-cash-stack"></i>
            </a>
            <a href="/setoran" class="w-nav w-tooltip" data-tip="Setoran"
                :class="{ 'active': path.startsWith('/setoran') }">
                <i class="bi bi-recycle"></i>
                <div class="w-notif-dot"></div>
            </a>

            <a href="/nasabah" class="w-nav w-tooltip" data-tip="Nasabah"
                :class="{ 'active': path.startsWith('/nasabah') }">
                <i class="bi bi-people-fill"></i>
            </a>

            <a href="/harga" class="w-nav w-tooltip" data-tip="Harga Sampah"
                :class="{ 'active': path.startsWith('/harga') }">
                <i class="bi bi-tags-fill"></i>
            </a>
            @if (Auth::user()->hasRole(['supervisor']))
                <a href="/kategori" class="w-nav w-tooltip" data-tip="Ketegori"
                    :class="{ 'active': path.startsWith('/kategori') }">
                    <i class="bi bi-grid-fill"></i>
                </a>
            @endif

            <a href="/grafik" class="w-nav w-tooltip" data-tip="Grafik/Laporan"
                :class="{ 'active': path.startsWith('/grafik') }">
                <i class="bi bi-graph-up-arrow"></i>
            </a>
        @endif
        <a href="/profile" class="w-nav w-tooltip" data-tip="Profile"
            :class="{ 'active': path.startsWith('/profile') }">
            <i class="bi bi-person-circle"></i>
        </a>
        <a href="/unit" class="w-nav w-tooltip" data-tip="Beranda" :class="{ 'active': path.startsWith('/unit') }">
            <i class="bi bi-bank"></i>
        </a>
        <a href="/organisasi" class="w-nav w-tooltip" data-tip="Organisasi"
            :class="{ 'active': path.startsWith('/organisasi') }">
            <i class="bi bi-building-check"></i>
        </a>

        <div class="mt-auto"></div>
        <a href="/setelan" class="w-nav w-tooltip" data-tip="Pengaturan"
            :class="{ 'active': path.startsWith('/setelan') }">
            <i class="bi bi-gear-fill"></i>
        </a>
    </aside>
</div>
