<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', fn(Request $request): Customer => $request->user())
            ->name('user');

        Route::apiResource('address', AddressController::class)
            ->except('show');

        Route::apiResource('identification', IdentificationController::class)
            ->except('show');

        Route::apiResource('social', SocialController::class)
            ->only(['index', 'destroy']);
    });

    Route::apiResource('branch', BranchController::class)
        ->only('index');
});
