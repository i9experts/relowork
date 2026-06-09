<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\Volunteer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // Super Admins have full access
        if (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()) {
            return $next($request);
        }

        // If a volunteer is inactive, force logout and redirect to org login with message
        if (! $user->is_admin) {
            $volunteer = Volunteer::where('user_id', $user->id)->first();
            if ($volunteer && strcasecmp((string) $volunteer->status, 'Active') !== 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $loginUrl = route('login');
                $org = $user->organization_id ? Organization::find($user->organization_id) : null;
                if ($org && $org->domain) {
                    if (app()->environment('local')) {
                        $loginUrl = 'http://localhost:8000/login?error='.urlencode('Your account is inactive. Please contact admin.').'&subdomain='.$org->domain;
                    } else {
                        $base = config('app.url');
                        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
                        $host = parse_url($base, PHP_URL_HOST) ?: 'api.reliowork.com';
                        $loginUrl = $scheme.'://'.$host.'/login?error='.urlencode('Your account is inactive. Please contact admin.').'&subdomain='.$org->domain;
                    }

                    return redirect()->to($loginUrl);
                }

                return redirect()->route('login')->with('error', 'Your account is inactive. Please contact admin.');
            }
        }

        // Super admin permission must be explicitly held
        if (in_array('super_admin', $permissions, true)) {
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

        // Allow access if ANY of the provided permissions is granted
        if (is_array($perms)) {
            foreach ($permissions as $permKey) {
                if ($perms[$permKey] ?? false) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('cms.dashboard.index')->with('error', 'Unauthorized to access this page.');
    }
}
