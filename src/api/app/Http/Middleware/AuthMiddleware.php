<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    const NAME = 'auth.web';

    public function handle(Request $request, Closure $next)
    {
        if ($request->getUser() != env('HTTP_USER') || $request->getPassword() != env('HTTP_PASS')) {
            $headers = ['WWW-Authenticate' => 'Basic'];

            return response('Unauthorized', 401, $headers);
        }

        return $next($request);
    }
}
