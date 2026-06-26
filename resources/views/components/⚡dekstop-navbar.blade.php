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
        @if (Auth::user()->hasRole(['admin', 'supervisor']))
            <a href="/penarikan" class="w-nav" :class="{ 'active': path.startsWith('/penarikan') }">
                <i class="bi bi-cash-stack"></i>
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
            @if (Auth::user()->hasRole(['supervisor']))
                <a href="/kategori" class="w-nav" :class="{ 'active': path.startsWith('/kategori') }">
                    <i class="bi bi-grid-fill"></i>
                </a>
            @endif

            <a href="/grafik" class="w-nav" :class="{ 'active': path.startsWith('/grafik') }">
                <i class="bi bi-graph-up-arrow"></i>
            </a>
        @endif
        <a href="/profile" class="w-nav" :class="{ 'active': path.startsWith('/profile') }">
            <i class="bi bi-person-circle"></i>
        </a>

        <div class="mt-auto"></div>
        <a href="/setelan" class="w-nav" :class="{ 'active': path.startsWith('/setelan') }">
            <i class="bi bi-gear-fill"></i>
        </a>
    </aside>
</div>
