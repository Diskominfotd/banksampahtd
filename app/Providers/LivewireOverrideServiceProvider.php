<?php

namespace App\Providers;

use App\Http\Controllers\CustomLivewireController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireOverrideServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->overrideRoutes();
    }

    public function overrideRoutes(): void
    {
        Livewire::setUploadRoute(function ($handle) {
            return Route::post('/livewire/upload-file', [CustomLivewireController::class, 'handle'])->middleware(['web']);
        });
    }
}
