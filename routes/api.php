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

        Route::apiResources([
            'address' => AddressController::class,
            'identification' => IdentificationController::class,
        ], [
            'except' => 'show',
        ]);

        Route::apiResources([
            'cart' => CartController::class,
            'wishlist' => WishlistController::class,
        ], [
            'except' => ['show', 'update'],
        ]);

        Route::apiResource('social', SocialController::class)
            ->only(['index', 'destroy']);
    });

    Route::apiResource('branch', BranchController::class)
        ->only('index');

    Route::get('filter-product', [FilterController::class, 'product']);

    Route::apiResource('product', ProductController::class)
        ->only(['index', 'show']);

    Route::get('filter-review/{product_id}', [FilterController::class, 'review']);

    Route::get('review/{product_id}', ReviewController::class);
});
