<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Apply JSON response middleware to all API routes
        $middleware->api(append: [
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
        
        // Enable Sanctum stateful API middleware for SPA authentication
        $middleware->statefulApi();
        
        // Register custom middleware aliases
        $middleware->alias([
            'role' => \Laratrust\Middleware\Role::class,
            'permission' => \Laratrust\Middleware\Permission::class,
            'ability' => \Laratrust\Middleware\Ability::class,
            'check.acl' => \App\Http\Middleware\CheckAcl::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
