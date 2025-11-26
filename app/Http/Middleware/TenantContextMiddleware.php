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
            if ($org) {
                $request->session()->put('organization_id', $org->id);
            }
        }

        return $next($request);
    }
}

