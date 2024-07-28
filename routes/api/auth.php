<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('login', fn(): RedirectResponse => redirect(config('app.frontend_url')))
    ->name('login');

require base_path('vendor/laravel/fortify/routes/routes.php');
