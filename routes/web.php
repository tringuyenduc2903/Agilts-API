<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::get('/', fn(): RedirectResponse => redirect(config('app.swagger_url')));

    Route::get('/login', fn(): RedirectResponse => redirect(config('app.frontend_url')))
        ->name('login');

    Route::get('/auth/redirect/{driver_name}', [AuthController::class, 'redirect'])
        ->name('auth.redirect');

    Route::get('/auth/callback/{driver_name}', [AuthController::class, 'callback']);

    require base_path('vendor/laravel/fortify/routes/routes.php');
});
