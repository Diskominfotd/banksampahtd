<?php

use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PanelController::class, 'home']);
Route::get('/nasabah', [PanelController::class, 'nasabah']);
Route::get('/setoran', [PanelController::class, 'home']);
Route::get('/kategori', [PanelController::class, 'home']);
