<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::prefix(LaravelLocalization::setLocale())
    ->group(function () {
        Route::get('/', fn(): RedirectResponse => redirect(config('app.swagger_url')));

        Route::get('/auth/redirect/{driver_name}', [AuthController::class, 'redirect'])
            ->name('auth.redirect');

        Route::get('/auth/callback/{driver_name}', [AuthController::class, 'callback']);

        require base_path('vendor/laravel/fortify/routes/routes.php');
    });
