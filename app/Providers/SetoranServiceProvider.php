<?php

namespace App\Providers;

use App\Services\Impl\SetoranServiceImpl;
use App\Services\SetoranService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SetoranServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        SetoranService::class => SetoranServiceImpl::class,
    ];

    public function provides(): array
    {
        return [SetoranService::class];
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
