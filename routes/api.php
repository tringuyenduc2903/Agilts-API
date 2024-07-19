<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', fn(Request $request): Customer => $request->user())
            ->name('user');

        Route::post('review-image', ReviewFileController::class);

        Route::apiResource('review-user', ReviewCustomerController::class);

        Route::apiResource('address', AddressController::class)
            ->except('show');

        Route::apiResource('identification', IdentificationController::class)
            ->except('show');

        Route::apiResource('social', SocialController::class)
            ->only(['index', 'destroy']);
    });

    Route::apiResource('branch', BranchController::class)
        ->only('index');

    Route::apiResource('filter-product', FilterProductController::class)
        ->only('index');

    Route::apiResource('product', ProductController::class)
        ->only(['index', 'show']);

    Route::apiResource('review', ReviewController::class)
        ->only('index');

    Route::get('filter-review/{product_id}', FilterReviewController::class);
});
