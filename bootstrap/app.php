<?php

use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use App\Http\Responses\TwoFactorEnabledResponse;
use CodeZero\LocalizedRoutes\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(remove: [
            SubstituteBindings::class,
        ]);
        $middleware->api(
            append: [
                SetLocale::class,
                SubstituteBindings::class,
            ],
            prepend: [
                EnsureFrontendRequestsAreStateful::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withBindings([
        \Laravel\Fortify\Http\Responses\LoginResponse::class => LoginResponse::class,
        \Laravel\Fortify\Http\Responses\TwoFactorEnabledResponse::class => TwoFactorEnabledResponse::class,
        \Laravel\Fortify\Http\Responses\LogoutResponse::class => LogoutResponse::class,
    ])
    ->create();
