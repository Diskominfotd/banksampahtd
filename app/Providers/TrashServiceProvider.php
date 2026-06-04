<?php

namespace App\Providers;

use App\Services\Impl\TrashServicesImpl;
use App\Services\TrashServices;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TrashServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        TrashServices::class => TrashServicesImpl::class,
    ];

    public function provides(): array
    {
        return [TrashServices::class];
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
