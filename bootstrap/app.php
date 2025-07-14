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
    ->withMiddleware(function (Middleware $middleware): void {
        
    // Middlewares pour les routes
    $middleware->alias([
        'etudiants' => \App\Http\Middleware\EtudiantsMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'secretaire' => \App\Http\Middleware\SecretaireMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
