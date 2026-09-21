<?php

use Livewire\Component;
use App\Services\UserServices;
use App\Models\User;
use App\Models\BankSampah;
use App\Models\Setoran;

new class extends Component {
    protected UserServices $userService;
    public string $nomorTelepon = '';
    public string $password = '';

    public function boot(UserServices $userService)
    {
        $this->userService = $userService;
    }
    public function doLogin()
    {
        $this->validate([
            'nomorTelepon' => 'required|string|max:13',
            'password' => 'required|min:6',
        ]);
        $this->userService->doLogin([
            'nomorTelepon' => $this->nomorTelepon,
            'password' => $this->password,
        ]);
    }

    public function getData()
    {
        $nasabah = User::where('status', 'active')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'nasabah');
            })
            ->count();
        $unit = BankSampah::count();
        $setoran = Setoran::sum('total_berat');

        return [
            'nasabah' => $nasabah,
            'unit' => $unit,
            'setoran' => $setoran,
        ];
    }
};
?>

<div>
    @php
        $data = $this->getData();
    @endphp
    <style>
        .unit-slideshow {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            width: 40%;
            min-width: 40%;
            max-width: 40%;
            flex-shrink: 0;
            box-sizing: border-box;
            height: 70px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .15);
        }

        .unit-slide {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 15px;
        }

        .unit-slide-thumb {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }

        .unit-slide-text {
            min-width: 0;
        }

        .unit-slide-nama {
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unit-slide-meta {
            display: flex;
            gap: 11px;
            font-size: 14px;
            color: rgba(255, 255, 255, .8);
            margin-top: 2px;
        }

        .unit-slide-meta span {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
        }

        .unit-slide-meta i {
            font-size: 13px;
        }

        .unit-slide-dots {
            position: absolute;
            bottom: 7px;
            right: 13px;
            display: flex;
            gap: 5px;
        }

        .unit-slide-dots button {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, .4);
            transition: .2s;
            padding: 0;
        }

        .unit-slide-dots button.active {
            background: #fff;
            width: 15px;
            border-radius: 3px;
        }

        @media (max-width: 768px) {
            .stat-chips {
                flex-wrap: wrap;
                gap: 8px;
            }

            .stat-chip {
                flex: 1 1 calc(33.333% - 8px);
                min-width: 0;
            }

            .unit-slideshow {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                height: 84px;
                margin-top: 6px;
                border-radius: 16px;
            }

            .unit-slide {
                padding: 0 16px;
                gap: 12px;
            }

            .unit-slide-thumb {
                width: 48px;
                height: 48px;
                font-size: 22px;
                border-radius: 12px;
            }

            .unit-slide-nama {
                font-size: 16px;
            }

            .unit-slide-meta {
                font-size: 16px;
                gap: 12px;
                margin-top: 4px;
            }

            .unit-slide-meta i {
                font-size: 14px;
            }

            .unit-slide-dots {
                bottom: 8px;
                right: 14px;
            }

            .unit-slide-dots button {
                width: 7px;
                height: 7px;
            }

            .unit-slide-dots button.active {
                width: 17px;
            }
        }

        @media (max-width: 480px) {
            .stat-chip-n {
                font-size: 18px;
            }

            .stat-chip-l {
                font-size: 10px;
            }

            .unit-slideshow {
                height: 78px;
            }

            .unit-slide {
                padding: 0 14px;
                gap: 10px;
            }

            .unit-slide-thumb {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }

            .unit-slide-nama {
                font-size: 15px;
            }

            .unit-slide-meta {
                font-size: 14px;
                gap: 10px;
            }
        }
    </style>
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
            <div class="brand-top">
                <div>
                    <div class="stat-chips">
                        <div class="brand-logo">
                            <img src="{{ asset('logotanahdatar.png') }}" alt="Logo" width="40" height="40">
                        </div>
                    </div>
                    <div class="brand-name">B-STAR</div>
                    <p class="brand-tagline">
                        Platform pengelolaan bank sampah digital yang membantu memantau setoran, nasabah, dan laporan
                        keuangan
                        secara real-time.
                    </p>
                </div>
                {{-- Ilustrasi versi desktop, otomatis disembunyikan di mobile lewat CSS --}}
                <img src="{{ asset('bup.png') }}" alt="Ilustrasi" class="brand-illustration">
            </div>
            <div class="stat-chips" style="margin-bottom:10px;">
                <div class="stat-chip">
                    <div class="stat-chip-n">{{ $data['nasabah'] }}</div>
                    <div class="stat-chip-l">Nasabah Aktif</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-n">{{ convertBeratToString($data['setoran']) }}</div>
                    <div class="stat-chip-l">Sampah 2026</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-n">{{ $data['unit'] }} Unit</div>
                    <div class="stat-chip-l">Cabang Aktif</div>
                </div>
            </div>

            <div class="unit-slideshow mb-2" x-data="{
                slides: [
                    { nama: 'Bank Sampah Batusangkar', nasabah: 32, sampah: '1,8 ton' },
                    { nama: 'Bank Sampah Lima Kaum', nasabah: 27, sampah: '2,1 ton' },
                    { nama: 'Bank Sampah Rambatan', nasabah: 19, sampah: '1,3 ton' },
                ],
                current: 0,
                startX: 0,
                endX: 0,
                interval: null,
                next() { this.current = (this.current + 1) % this.slides.length },
                prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length },
                handleSwipe() {
                    const diff = this.startX - this.endX;
                    if (Math.abs(diff) > 40) { diff > 0 ? this.next() : this.prev(); }
                },
                startAuto() { this.interval = setInterval(() => this.next(), 4000); },
                stopAuto() { clearInterval(this.interval); }
            }" x-init="startAuto()"
                @touchstart="startX = $event.touches[0].clientX; stopAuto()"
                @touchend="endX = $event.changedTouches[0].clientX; handleSwipe(); startAuto()">

                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="current === index" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="unit-slide">
                        <div class="unit-slide-thumb">
                            <i class="bi bi-bank2"></i>
                        </div>
                        <div class="unit-slide-text">
                            <div class="unit-slide-nama" x-text="slide.nama"></div>
                            <div class="unit-slide-meta">
                                <span><i class="bi bi-people-fill"></i> <span x-text="slide.nasabah"></span>
                                    Nasabah</span>
                                <span><i class="bi bi-recycle"></i> <span x-text="slide.sampah"></span></span>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="unit-slide-dots">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" @click="current = index"
                            :class="{ 'active': current === index }"></button>
                    </template>
                </div>
            </div>
            <div class="left-footer mt-auto">
                © 2026 B-STAR - Bank Sampah Tanah Datar Pintar · Kabupaten Tanah Datar
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
                    <label for="nomorTelepon" class="form-label">Nomor Telepon</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="nomorTelepon" wire:model="nomorTelepon"
                            class="form-control @error('nomorTelepon') is-invalid @enderror" placeholder="08xxxxxxxxxx"
                            maxlength="13" autocomplete="username" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                        @error('nomorTelepon')
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
                    {{-- <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe" style="font-size:12px;">
                            Ingat saya
                        </label>
                    </div>
                    <a class="forgot-link" onclick="showForgot()">Lupa kata sandi?</a> --}}
                </div>
                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                    id="btn-login" type="submit" style="padding:12px; border-radius:12px;"
                    wire:loading.attr="disabled" wire:target="doLogin">
                    <span wire:loading.remove wire:target="doLogin">Masuk</span>
                    <div wire:loading wire:target="doLogin"
                        style="width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;">
                    </div>
                    <span wire:loading wire:target="doLogin">Memproses...</span>
                    <i class="bi bi-arrow-right-short fs-5" wire:loading.remove wire:target="doLogin"></i>
                </button>
                <div class="text-center mt-3" style="font-size:14px;">
                    Belum punya akun? <a href="{{ route('daftar') }}" class="fw-semibold text-decoration-none">Silahkan bergabung
                        disini</a>
                </div>
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
