<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // Add this line
use App\Http\Middleware\CheckPendingAgent; // Add this line

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add your middleware alias here
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'check.pending.agent' => CheckPendingAgent::class,
        ]);

        $middleware->web(append: [
            CheckPendingAgent::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();