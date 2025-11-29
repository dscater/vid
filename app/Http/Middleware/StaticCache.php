<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // if (preg_match('/\.(mp4|webm|jpg|png|gif)$/', $request->path())) {
        //     $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        // }

        return $response;
    }
}
