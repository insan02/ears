<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // PERBAIKAN UNTUK GOOGLE CLOUD LOAD BALANCER
        $middleware->trustProxies(at: '*');

        $middleware->redirectUsersTo('/beranda');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
