<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticCORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() === "OPTIONS") {
            return response()->json('OK', 200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
            ]);
        }

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');

        return $response;
    }
}
