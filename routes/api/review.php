<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('review-image', ReviewFileController::class);

    Route::apiResource('review-user', ReviewCustomerController::class);
});

Route::get('filter-review/{product_id}', [FilterController::class, 'review']);

Route::get('review/{product_id}', ReviewController::class);
