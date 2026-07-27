<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use App\Http\Controllers\CustomLivewireController;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        Carbon::setLocale(config('app.locale'));
        app()->setLocale(config('app.locale'));
        $this->configureDefaults();
        Paginator::defaultView('vendor.pagination.bootstrap-5');

        Livewire::setUploadRoute(function ($handle) {
            return Route::post('/livewire/upload-file', $handle)
                ->middleware(['web'])
                ->name('livewire.upload-file');
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);
        DB::prohibitDestructiveCommands(app()->isProduction());
        Password::defaults(fn(): ?Password => app()->isProduction() ? Password::min(12)->mixedCase()->letters()->numbers()->symbols()->uncompromised() : null);
    }
}