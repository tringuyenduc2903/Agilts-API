<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', fn(Request $request): Customer => $request->user())
                ->name('user');

            Route::get('file/image/{path}/{file_name}', [FileController::class, 'image'])
                ->name('image');

            Route::post('file/image', [FileController::class, 'uploadImage']);
        });

        Route::apiResource('address', AddressController::class)
            ->except('show');

        Route::apiResource('identification', IdentificationController::class)
            ->except('show');

        Route::apiResource('social', SocialController::class)
            ->only(['index', 'destroy']);
    });

    Route::apiResource('branch', BranchController::class)
        ->only('index');

    Route::apiResource('filter', FilterController::class)
        ->only('index');

    Route::apiResource('product', ProductController::class)
        ->only(['index', 'show']);
});
