<?php

use Livewire\Component;
use App\Services\UserServices;

new class extends Component {
    protected UserServices $userService;
    public string $nik = '';
    public string $password = '';

    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }
    public function doLogin()
    {
        $this->validate([
            'nik' => 'required|string|max:16',
            'password' => 'required|min:6',
        ]);
        $this->userService->doLogin([
            'nik' => $this->nik,
            'password' => $this->password,
        ]);
    }
};
?>

<div>
    <!-- Background -->
    <div class="bg-layer">
        <div class="bg-blob b1"></div>
        <div class="bg-blob b2"></div>
        <div class="bg-blob b3"></div>
        <div class="bg-blob b4"></div>
        <div class="leaf l1"></div>
        <div class="leaf l2"></div>
        <div class="leaf l3"></div>
    </div>

    <div class="login-wrap">

        <!-- ══ LEFT PANEL ══ -->
        <div class="left-panel">
            <div class="brand-logo"><i class="bi bi-recycle text-white"></i></div>
            <div class="brand-name">Bank Sampah<br>Nusantara</div>
            <p class="brand-tagline">
                Platform pengelolaan bank sampah digital yang membantu memantau setoran, nasabah, dan laporan keuangan
                secara real-time.
            </p>
            <div class="stat-chips">
                <div class="stat-chip">
                    <div class="stat-chip-n">128</div>
                    <div class="stat-chip-l">Nasabah Aktif</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-n">8,2 T</div>
                    <div class="stat-chip-l">Sampah 2026</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-n">6 Unit</div>
                    <div class="stat-chip-l">Cabang Aktif</div>
                </div>
            </div>
            <div class="left-footer mt-auto">
                © 2026 Bank Sampah Nusantara · Kabupaten Tanah Datar
            </div>
        </div>

        <!-- ══ RIGHT PANEL ══ -->
        <div class="right-panel">

            <div class="mb-4">
                <div class="form-eyebrow">Selamat datang kembali</div>
                <div class="form-title">Masuk ke Sistem</div>
                <p class="form-sub">Masukkan kredensial Anda untuk mengakses dashboard pengelola.</p>
            </div>

            {{-- Session error --}}
            @if (session()->has('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="doLogin" class="form-area" id="form-area">
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="nik" wire:model="nik"
                            class="form-control @error('nik') is-invalid @enderror" placeholder="13xxxxxxxxxxxx"
                            maxlength="16" autocomplete="username">
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group" x-data="{ show: false }">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input :type="show ? 'text' : 'password'" id="password" wire:model="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi" autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" tabindex="-1" @click="show = !show">
                            <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe" style="font-size:12px;">
                            Ingat saya
                        </label>
                    </div>
                    <a class="forgot-link" onclick="showForgot()">Lupa kata sandi?</a>
                </div>
                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                    id="btn-login" type="submit" style="padding:12px; border-radius:12px;" wire:loading.attr="disabled"
                    wire:target="doLogin">
                    <span wire:loading.remove wire:target="doLogin">Masuk</span>
                    <div wire:loading wire:target="doLogin"
                        style="width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;">
                    </div>
                    <span wire:loading wire:target="doLogin">Memproses...</span>
                    <i class="bi bi-arrow-right-short fs-5" wire:loading.remove wire:target="doLogin"></i>
                </button>
            </form>

            {{-- Success state --}}
            <div class="success-state" id="success-state">
                <div class="success-ico"><i class="bi bi-check2"></i></div>
                <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--text-main)">Login
                    Berhasil!</div>
                <div style="font-size:12px;color:var(--muted)">Mengalihkan ke dashboard…</div>
                <div class="d-flex gap-2 align-items-center mt-2">
                    <div
                        style="width:6px;height:6px;border-radius:50%;background:var(--cyan);animation:dot1 1s 0s infinite">
                    </div>
                    <div
                        style="width:6px;height:6px;border-radius:50%;background:var(--cyan);animation:dot1 1s .2s infinite">
                    </div>
                    <div
                        style="width:6px;height:6px;border-radius:50%;background:var(--cyan);animation:dot1 1s .4s infinite">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
