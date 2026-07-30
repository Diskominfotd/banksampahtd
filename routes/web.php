<?php

use App\Http\Controllers\CustomeFunctionController;
use App\Http\Controllers\PanelController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\LevelOne;
use App\Http\Middleware\LevelTwo;
use App\Http\Middleware\MultiLevel;
use Illuminate\Support\Facades\Route;

// Route::get('/testing', [CustomeFunctionController::class, 'testing']);

Route::get('/debug-scheme', function () {
    return [
        'scheme' => request()->getScheme(),
        'secure' => request()->isSecure(),
        'x_fwd_proto' => request()->header('X-Forwarded-Proto'),
    ];
});
Route::get('/login', [PanelController::class, 'login'])
    ->name('login')
    ->middleware('throttle:20,1');

Route::middleware([Authenticate::class, 'throttle:100,1'])->group(function (): void {
    Route::get('/', [PanelController::class, 'home'])->name('home');
    Route::get('/profile', [PanelController::class, 'profile'])->name('profile');

    Route::middleware(MultiLevel::class)->group(function (): void {
        Route::get('/nasabah', [PanelController::class, 'nasabah'])->name('nasabah');
        Route::get('/setoran', [PanelController::class, 'setoran'])->name('setoran');
        Route::get('/harga', [PanelController::class, 'harga'])->name('harga');
        Route::get('/grafik', [PanelController::class, 'grafik'])->name('grafik');
        Route::get('/penarikan', [PanelController::class, 'penarikanSaldo'])->name('penarikan.saldo');

        Route::middleware(LevelOne::class)->group(function (): void {
            Route::get('/kategori', [PanelController::class, 'kategori'])->name('kategori');
            Route::get('/unit', [PanelController::class, 'unit'])->name('unit');
            Route::get('/organisasi', [PanelController::class, 'organisasi'])->name('organisasi');
        });

        Route::middleware(LevelTwo::class)->group(function (): void {
            Route::get('/gudang', [PanelController::class, 'gudang'])->name('gudang');
            Route::get('/setoran/pencatatan', [PanelController::class, 'catatSetoran'])->name('setoran.catat');
            Route::get('/penarikan/buat-penarikan', [PanelController::class, 'buatPenarikan'])->name('buat.penarikan.saldo');
        });
    });
});
