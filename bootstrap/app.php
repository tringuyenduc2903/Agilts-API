<?php

use App\Http\Responses\LoginResponse;
use CodeZero\LocalizedRoutes\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
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
        $middleware->web(remove: [
            SubstituteBindings::class,
        ]);
        $middleware->web(append: [
            SetLocale::class,
            SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withBindings([
        \Laravel\Fortify\Http\Responses\LoginResponse::class => LoginResponse::class,
    ])
    ->create();
