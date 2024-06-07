<?php

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::prefix(LaravelLocalization::setLocale())
    ->group(function () {
        Route::get('user', fn(Request $request): Customer => $request->user())
            ->middleware('auth:sanctum');
    });
