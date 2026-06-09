<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <aside class="w-sidebar" x-data="{ path: window.location.pathname }">

        <a href="/" class="w-nav" :class="{ 'active': path === '/' }">
            <i class="bi bi-house-fill"></i>
        </a>

        <a href="/setoran" class="w-nav" :class="{ 'active': path.startsWith('/setoran') }">
            <i class="bi bi-recycle"></i>
            <div class="w-notif-dot"></div>
        </a>

        <a href="/nasabah" class="w-nav" :class="{ 'active': path.startsWith('/nasabah') }">
            <i class="bi bi-people-fill"></i>
        </a>

        <a href="/harga" class="w-nav" :class="{ 'active': path.startsWith('/harga') }">
            <i class="bi bi-tags-fill"></i>
        </a>

        <a href="/kategori" class="w-nav" :class="{ 'active': path.startsWith('/kategori') }">
            <i class="bi bi-grid-fill"></i>
        </a>

        <a href="/laporan" class="w-nav" :class="{ 'active': path.startsWith('/laporan') }">
            <i class="bi bi-graph-up-arrow"></i>
        </a>

        <div class="mt-auto"></div>

        <a href="/setelan" class="w-nav" :class="{ 'active': path.startsWith('/setelan') }">
            <i class="bi bi-gear-fill"></i>
        </a>

    </aside>
</div>
