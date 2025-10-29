<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAcl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Dapatkan nama route
        $routeName = $request->route()->getName();

        // Admin role bypass - admin bisa akses semua route
        if ($user->hasRole('admin')) {
            \Illuminate\Support\Facades\Log::debug('Admin bypass for route: ' . $routeName);
            return $next($request);
        }

        if (!$routeName) {
            return response()->json([
                'message' => 'Route name is required for ACL check'
            ], 403);
        }

        // Cek permission berdasarkan nama route menggunakan Laratrust
        if (!$user->hasPermission($routeName)) {
            return response()->json([
                'message' => 'You do not have permission to access this resource',
                'required_permission' => $routeName
            ], 403);
        }

        return $next($request);
    }
}
