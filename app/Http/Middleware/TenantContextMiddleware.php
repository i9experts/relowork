<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        $subdomain = null;
        if (app()->environment('local')) {
            if (count($parts) === 2 && $parts[1] === 'localhost') {
                $subdomain = $parts[0];
            }
        } else {
            $base = config('app.url');
            $baseHost = parse_url($base, PHP_URL_HOST) ?: 'api.reliowork.com';

            if ($host === $baseHost) {
                // Base host (e.g. api.reliowork.com) -> no tenant context
                $subdomain = null;
            } elseif (str_ends_with($host, '.'.$baseHost)) {
                // Tenant host (e.g. org.api.reliowork.com) -> use the left-most label
                $subdomain = $parts[0];
            } else {
                // Any other host pattern -> no tenant
                $subdomain = null;
            }
        }

        // Check for subdomain from URL parameter (for both local and production)
        if (!$subdomain && $request->has('subdomain')) {
            $urlSubdomain = $request->get('subdomain');
            if ($urlSubdomain) {
                $subdomain = $urlSubdomain;
            }
        }

        if ($subdomain) {
            $org = Organization::where('domain', $subdomain)->first();
            if (! $org) {
                // Don't abort if super admin is trying to login
                $user = $request->user();
                if ($user && method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()) {
                    return $next($request);
                }
                abort(404);
            }

            $request->session()->put('organization_id', $org->id);

            $user = $request->user();
            $isSuper = ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()));
            if ($user && ! $isSuper && (int) $user->organization_id !== (int) $org->id) {
                $targetOrg = \App\Models\Organization::find($user->organization_id);
                if ($targetOrg && $targetOrg->domain) {
                    if (app()->environment('local')) {
                        $redirectUrl = 'http://localhost:8000/dashboard?subdomain='.$targetOrg->domain;
                        return redirect()->to($redirectUrl);
                    } else {
                        $base = config('app.url');
                        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
                        $host = parse_url($base, PHP_URL_HOST) ?: 'api.reliowork.com';
                        $redirectUrl = $scheme.'://'.$host.'/dashboard?subdomain='.$targetOrg->domain;
                        return redirect()->to($redirectUrl);
                    }
                }
                abort(404);
            }
        }

        return $next($request);
    }
}
