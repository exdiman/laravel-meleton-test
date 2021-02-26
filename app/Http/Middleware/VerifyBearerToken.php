<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class VerifyBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $expectedToken = env('BEARER_TOKEN');

        if (! $expectedToken) {
            throw new AuthorizationException('BEARER_TOKEN not configured');
        }

        if ($expectedToken !== $token) {
            throw new AuthorizationException('Invalid authorization token');
        }

        return $next($request);
    }
}
