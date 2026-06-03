<aside class="w-sidebar">

    <a href="/" class="w-nav {{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i>
    </a>

    <a href="/setoran" class="w-nav {{ request()->is('setoran') ? 'active' : '' }}">
        <i class="bi bi-recycle"></i>
        <div class="w-notif-dot"></div>
    </a>

    <a href="/nasabah" class="w-nav {{ request()->is('nasabah') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i>
    </a>
    <a href="/harga" class="w-nav {{ request()->is('harga') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i>
    </a>
    <a href="/kategori" class="w-nav {{ request()->is('kategori') ? 'active' : '' }}">
        <i class="bi bi-grid-fill"></i>
    </a>
    <a href="/laporan" class="w-nav {{ request()->is('laporan') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow"></i>
    </a>

    <div class="mt-auto"></div>

    <a href="/setelan" class="w-nav {{ request()->is('setelan') ? 'active' : '' }}">
        <i class="bi bi-gear-fill"></i>
    </a>

</aside>
