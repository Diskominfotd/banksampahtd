<nav class="m-bottom-nav">
    <a href="/" class="m-nav-btn {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i><span>Beranda</span>
    </a>
    <a href="/setoran" class="m-nav-btn {{ request()->is('setoran*') ? 'active' : '' }}">
        <i class="bi bi-recycle"></i><span>Setoran</span>
    </a>
    <a href="/nasabah" class="m-nav-btn {{ request()->is('nasabah*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i><span>Nasabah</span>
    </a>
    <a href="/profil" class="m-nav-btn {{ request()->is('profil*') ? 'active' : '' }}">
        <i class="bi bi-person-fill"></i><span>Profil</span>
    </a>
</nav>
