<?php

use App\Http\Middleware\PermisoUsuarioMiddleware;
use App\Http\Middleware\StaticCache;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Http\Response as ResponseHttp;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            StaticCache::class,
        ]);
        $middleware->alias([
            'permisoUsuario' => PermisoUsuarioMiddleware::class,
            'nocache' => \App\Http\Middleware\NoCache::class,
            'staticCache' => \App\Http\Middleware\StaticCache::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejar TokenExpiredException
        $exceptions->renderable(function (TokenExpiredException $e, $request) {
            return response()->json([
                'error' => 'token_expired',
                'message' => 'El token ha expirado y ya no se puede utilizar.'
            ], ResponseHttp::HTTP_UNAUTHORIZED);
        });

        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 401) {
                return Inertia::render("Errors/401");
            }
            return $response;
        });
    })->create();
