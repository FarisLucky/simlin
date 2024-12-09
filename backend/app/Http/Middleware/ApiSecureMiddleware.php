<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiSecureMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tokenValid = User::where('api_key', $request->header('Authorization'))->exists();

        if (!$tokenValid) {
            return response()->json('Unauthorized', 401);
        }

        return $next($request);
    }
}
