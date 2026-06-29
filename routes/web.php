<?php

use App\Http\Controllers\PanelController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Route::middleware(['throttle:global'])->group(function () {
Route::get('/login', [PanelController::class, 'login'])->name('login');
Route::middleware(Authenticate::class)->group(function (): void {
    Route::get('/', [PanelController::class, 'home'])->name('home');
    Route::get('/nasabah', [PanelController::class, 'nasabah'])->name('nasabah');
    Route::get('/setoran', [PanelController::class, 'setoran'])->name('setoran');
    Route::get('/setoran/pencatatan', [PanelController::class, 'catatSetoran'])->name('setoran.catat');
    Route::get('/kategori', [PanelController::class, 'kategori'])->name('kategori');
    Route::get('/harga', [PanelController::class, 'harga'])->name('harga');
    Route::get('/penarikan', [PanelController::class, 'penarikanSaldo'])->name('penarikan.saldo');
    Route::get('/penarikan/buat-penarikan', [PanelController::class, 'buatPenarikan'])->name('buat.penarikan.saldo');
    Route::get('/profile', [PanelController::class, 'profile'])->name('profile');
    Route::get('/grafik', [PanelController::class, 'grafik'])->name('grafik');
    Route::get('/organisasi', [PanelController::class, 'organisasi'])->name('organisasi');
    Route::get('/unit', [PanelController::class, 'unit'])->name('unit');
    Route::get('/gudang', [PanelController::class, 'gudang'])->name('gudang');
});
// });
