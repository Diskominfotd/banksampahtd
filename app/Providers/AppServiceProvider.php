<?php

namespace App\Providers;
use Carbon\CarbonImmutable;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            Log::info('PULSE GATE CHECK', [
                'user_class' => get_class($user),
                'user_id' => $user->id,
                'has_role' => $user->hasRole(['supervisor']),
                'roles' => $user->roles->pluck('name'),
            ]);
            return $user->hasRole(['supervisor']);
        });
        Request::macro('hasValidSignature', function ($absolute = true) {
            $uploading = strpos(URL::current(), '/livewire/upload-file');
            $previewing = strpos(URL::current(), '/livewire/preview-file');
            if ($uploading || $previewing) {
                return true;
            }
        });
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Carbon::setLocale(config('app.locale'));
        app()->setLocale(config('app.locale'));
        $this->configureDefaults();
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(app()->isProduction());

        Password::defaults(fn(): ?Password => app()->isProduction() ? Password::min(12)->mixedCase()->letters()->numbers()->symbols()->uncompromised() : null);
    }
}
