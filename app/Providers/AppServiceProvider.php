<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

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
        ResetPassword::createUrlUsing(fn(object $notifiable, string $token) => sprintf(
            '%s/password-reset/%s?email=%s',
            config('app.frontend_url'),
            $token,
            $notifiable->getEmailForPasswordReset()
        ));
    }
}
