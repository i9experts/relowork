<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Admins have full access
        if (property_exists($user, 'is_admin') && $user->is_admin) {
            return $next($request);
        }

        $perms = $user->permissions ?? [];
        if (is_string($perms)) {
            $decoded = json_decode($perms, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $perms = $decoded;
            }
        }

        if (is_array($perms) && ($perms[$permission] ?? false)) {
            return $next($request);
        }

        abort(403);
    }
}
