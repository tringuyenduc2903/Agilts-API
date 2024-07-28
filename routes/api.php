<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::apiResource('branch', BranchController::class)
        ->only('index');

    require __DIR__ . '/api/auth.php';
    require __DIR__ . '/api/user.php';
    require __DIR__ . '/api/product.php';
    require __DIR__ . '/api/review.php';
});
