<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }

    /**
     * Handle unauthenticated requests for API clients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array<int, string>  $guards
     * @return void
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing token.',
                ], Response::HTTP_UNAUTHORIZED)
            );
        }

        parent::unauthenticated($request, $guards);
    }
}


