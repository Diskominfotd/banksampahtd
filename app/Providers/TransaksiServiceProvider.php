<?php

namespace App\Providers;

use App\Services\Impl\TransaksiServiceImpl;
use App\Services\TransaksiService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TransaksiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        TransaksiService::class => TransaksiServiceImpl::class,
    ];

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
