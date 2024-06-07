<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::prefix(LaravelLocalization::setLocale())
    ->group(function () {
        Route::get('/', fn(): RedirectResponse => redirect(config('app.swagger_url')));

        require base_path('vendor/laravel/fortify/routes/routes.php');
    });
