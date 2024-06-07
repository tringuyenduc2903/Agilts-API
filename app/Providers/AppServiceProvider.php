<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

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
        ResetPassword::createUrlUsing(fn(object $notifiable, string $token) => sprintf(
            '%s/password-reset/%s?email=%s',
            config('app.frontend_url'),
            $token,
            $notifiable->getEmailForPasswordReset()
        ));

        RouteServiceProvider::loadCachedRoutesUsing(fn() => $this->loadCachedRoutes());
    }
}
