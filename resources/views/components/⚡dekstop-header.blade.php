<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
new class extends Component {

};
?>

<div>
    <header class="w-topbar">
        <div id="w-topbar-info">
            <div class="w-title">Dashboard Pengelola</div>
            <div class="w-sub">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} · {{ Auth::user()->unit->nama }}
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="w-search" style="visibility: hidden;"><i class="bi bi-search si"></i><input type="text"
                    placeholder="Cari nasabah, setoran...">
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end">
                    <div class="w-uname">{{ ucfirst(Auth::user()->name) }}</div>
                    <div class="w-urole">{{ Auth::user()->unit->nama }}</div>
                </div>

                {{-- Avatar + Dropdown --}}
                <div x-data="{ open: false }" class="position-relative">
                    <div class="avatar avatar-sm" @click="open = !open" @click.outside="open = false"
                        style="cursor: pointer; user-select: none;">
                        {{ strtoupper(Auth::user()->initials()) }}
                    </div>

                    <div x-show="open" x-transition
                        style="position: absolute; right: 0; top: calc(100% + 0.5rem); min-width: 180px; z-index: 999;"
                        class="bg-white rounded shadow border">

                        <div class="px-3 py-2 border-bottom">
                            <div class="fw-semibold small">{{ ucfirst(Auth::user()->name) }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="border-top">
                            <button wire:click="logout"
                                class="d-flex align-items-center gap-2 px-3 py-2 small text-danger border-0 bg-transparent w-100 text-start"
                                style="transition: background .15s;" onmouseover="this.style.background='#fff5f5'"
                                onmouseout="this.style.background=''">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>
</div>
