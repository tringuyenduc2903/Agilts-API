<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::get('user', fn(Request $request): Customer => $request->user())
        ->middleware('auth:sanctum')
        ->name('user');

    Route::apiResource('branch', BranchController::class)
        ->only(['index', 'show']);

    Route::apiResource('address', AddressController::class)
        ->except('show')
        ->middleware('auth:sanctum');

    Route::apiResource('social', SocialController::class)
        ->only(['index', 'destroy'])
        ->middleware('auth:sanctum');
});
