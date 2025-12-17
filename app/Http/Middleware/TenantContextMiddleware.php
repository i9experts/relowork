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
            if (count($parts) >= 3) {
                $subdomain = $parts[0];
            }
        }

        if ($subdomain) {
            $org = Organization::where('domain', $subdomain)->first();
            if (! $org) {
                abort(404);
            }

            $request->session()->put('organization_id', $org->id);

            $user = $request->user();
            $isSuper = ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()));
            if ($user && ! $isSuper && (int) $user->organization_id !== (int) $org->id) {
                $targetOrg = \App\Models\Organization::find($user->organization_id);
                if ($targetOrg && $targetOrg->domain) {
                    if (app()->environment('local')) {
                        $tenantHost = $targetOrg->domain.'.localhost:8000';
                        return redirect()->to('http://'.$tenantHost.'/dashboard');
                    } else {
                        $base = config('app.url');
                        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
                        $host = parse_url($base, PHP_URL_HOST) ?: 'relowork.com';
                        $tenantHost = $targetOrg->domain.'.'.$host;
                        return redirect()->to($scheme.'://'.$tenantHost.'/dashboard');
                    }
                }
                abort(404);
            }
        }

        return $next($request);
    }
}
