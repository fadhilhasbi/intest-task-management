<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param string $role
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah pengguna sudah login
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Cek role pengguna
        if (auth()->user()->role !== $role) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
