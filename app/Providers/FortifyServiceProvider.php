<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewCustomer;
use App\Actions\Fortify\ResetCustomerPassword;
use App\Actions\Fortify\UpdateCustomerPassword;
use App\Actions\Fortify\UpdateCustomerProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewCustomer::class);
        Fortify::updateUserProfileInformationUsing(UpdateCustomerProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateCustomerPassword::class);
        Fortify::resetUserPasswordsUsing(ResetCustomerPassword::class);
    }
}
