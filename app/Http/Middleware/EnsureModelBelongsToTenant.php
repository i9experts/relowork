<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModelBelongsToTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $isSuper = ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin()));
        if ($isSuper) {
            return $next($request);
        }

        $tenantId = optional($user)->organization_id ?? $request->session()->get('organization_id');
        $params = $request->route() ? $request->route()->parameters() : [];
        foreach ($params as $param) {
            if ($param instanceof Model) {
                if ($param->getAttribute('organization_id') !== null && $param->getAttribute('organization_id') !== $tenantId) {
                    abort(404);
                }
            }
        }

        return $next($request);
    }
}

