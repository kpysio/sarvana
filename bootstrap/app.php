<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'provider' => \App\Http\Middleware\ProviderMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'active.membership' => \App\Http\Middleware\ActiveMembershipMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'set.intended.url' => \App\Http\Middleware\SetIntendedUrl::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
