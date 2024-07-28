<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::get('/', fn(): RedirectResponse => redirect(config('app.frontend_url')));

    Route::prefix('auth')
        ->middleware('guest')
        ->group(function () {
            Route::get('redirect/{driver_name}', [AuthController::class, 'redirect'])
                ->name('auth.redirect');

            Route::get('callback/{driver_name}', [AuthController::class, 'callback']);
        });
});
