<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('filter-product', [FilterController::class, 'product']);

Route::apiResource('product', ProductController::class)
    ->only(['index', 'show']);
