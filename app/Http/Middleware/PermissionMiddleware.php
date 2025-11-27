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
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // Super Admins have full access
        if (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()) {
            return $next($request);
        }

        // Super admin permission must be explicitly held
        if ($permission === 'super_admin') {
            return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized: Super Admin access required.');
        }

        $perms = $user->permissions ?? [];
        if (is_string($perms)) {
            $decoded = json_decode($perms, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $perms = $decoded;
            }
        }

        // Organization Admins have full access to org-level modules
        if ($user->is_admin) {
            return $next($request);
        }

        if (is_array($perms) && ($perms[$permission] ?? false)) {
            return $next($request);
        }

        return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized to access this page.');
    }
}
