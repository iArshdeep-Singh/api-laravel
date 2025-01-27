<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Human;

class TokenAuthenticator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->header('Authorization');

        if (!$access_token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Human::where('access_token', $access_token)->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // $request->merge(['user' => $user]);

        return $next($request);
    }
}
