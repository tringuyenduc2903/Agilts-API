<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', fn(Request $request): Customer => $request->user())
        ->name('user');

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

    Route::apiResource('order', OrderController::class)
        ->only(['index', 'show', 'store']);

    Route::apiResource('social', SocialController::class)
        ->only(['index', 'destroy']);
});
