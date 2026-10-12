<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\RoleMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // === 403 ===
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) return null;
            return response()->view('errors.403', [], 403);
        });

        // === 404 ===
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) return null;

            if (view()->exists('errors.404')) {
                return response()->view('errors.404', [], 404);
            }
            return null;
        });

        // === 419 ===
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) return null;

            if (view()->exists('errors.419')) {
                return response()->view('errors.419', [], 419);
            }
            return null;
        });

        // === 500 ===
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) return null;

            // Jangan override HTTP exception lain (403, 404, dll)
            if ($e instanceof HttpException) {
                return null;
            }

            if (view()->exists('errors.500')) {
                return response()->view('errors.500', [], 500);
            }
            return null;
        });

    })
    ->create();