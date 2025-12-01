<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InactiveLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, just continue.
        if (! $request->user()) {
            return $next($request);
        }

        // Resolve organization id the same way as in SettingController
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');

        if ($organizationId) {
            $setting = Setting::where('organization_id', $organizationId)->first();
        } else {
            $setting = null;
        }

        // If no settings found or session timeout not enabled, just continue.
        if (! $setting || ! $setting->session_timeout_enabled) {
            return $next($request);
        }

        $now = now()->timestamp;
        $lastActivity = (int) $request->session()->get('last_activity_timestamp', $now);

        // Use configurable timeout in minutes, defaulting to 10 if not set
        $timeoutMinutes = (int) ($setting->session_timeout_minutes ?? 10);
        if ($timeoutMinutes <= 0) {
            $timeoutMinutes = 10;
        }

        $timeoutSeconds = $timeoutMinutes * 60;

        if (($now - $lastActivity) > $timeoutSeconds) {
            // Forget last activity and logout user
            $request->session()->forget('last_activity_timestamp');
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'You have been logged out due to inactivity.');
        }

        // Update last activity time
        $request->session()->put('last_activity_timestamp', $now);

        return $next($request);
    }
}
