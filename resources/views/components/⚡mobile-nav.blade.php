<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <nav class="m-bottom-nav" x-data="{ path: window.location.pathname }">
        <a href="/" class="m-nav-btn" :class="{ 'active': path === '/' }">
            <i class="bi bi-house-fill"></i><span>Beranda</span>
        </a>
        <a href="/setoran" class="m-nav-btn" :class="{ 'active': path.startsWith('/setoran') }">
            <i class="bi bi-recycle"></i><span>Setoran</span>
        </a>
        <a href="/nasabah" class="m-nav-btn" :class="{ 'active': path.startsWith('/nasabah') }">
            <i class="bi bi-people-fill"></i><span>Nasabah</span>
        </a>
        <a href="/profil" class="m-nav-btn" :class="{ 'active': path.startsWith('/profil') }">
            <i class="bi bi-person-fill"></i><span>Profil</span>
        </a>
        <a href="/unit" class="m-nav-btn" :class="{ 'active': path.startsWith('/unit') }">
            <i class="bi bi-bank"></i></i><span>Bank/Unit</span>
        </a>
        <a href="/organisasi" class="m-nav-btn" :class="{ 'active': path.startsWith('/organisasi') }">
            <i class="bi bi-person-fill"></i><span>Organisasi</span>
        </a>
    </nav>
</div>
