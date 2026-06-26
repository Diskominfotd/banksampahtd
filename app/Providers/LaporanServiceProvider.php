<?php

namespace App\Providers;

use App\Services\Impl\LaporanServiceImpl;
use App\Services\LaporanService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LaporanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        LaporanService::class => LaporanServiceImpl::class,
    ];

    public function provides(): array
    {
        return [LaporanService::class];
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
