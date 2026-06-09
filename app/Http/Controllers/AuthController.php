<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Mail\SystemNotificationMailer;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $u = Auth::user();
            if ($u && (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin())) {
                return redirect()->route('cms.admin.dashboard');
            }

            if ($u && $u->is_admin) {
                return redirect()->route('cms.dashboard.index');
            }

            if ($u && $u->hasPermission('volunteers')) {
                return redirect()->route('cms.volunteer.index');
            }
            if ($u && $u->hasPermission('families')) {
                return redirect()->route('cms.family.index');
            }
            if ($u && $u->hasPermission('aid_programs')) {
                return redirect()->route('cms.aid-program.index');
            }
            if ($u && $u->hasPermission('financial')) {
                return redirect()->route('cms.financial-management.index');
            }
            if ($u && $u->hasPermission('surveys')) {
                return redirect()->route('cms.survey.index');
            }
            if ($u && $u->hasPermission('reports')) {
                return redirect()->route('cms.report.index');
            }

            if ($u && $u->hasPermission('dashboard')) {
                return redirect()->route('cms.dashboard.index');
            }

            return redirect()->route('cms.profile.index');
        }

        $organization = null;
        $subdomain = null;

        $host = request()->getHost();
        $parts = explode('.', $host);

        if (app()->environment('local')) {
            // {subdomain}.localhost
            if (count($parts) === 2 && $parts[1] === 'localhost') {
                $subdomain = $parts[0];
                $organization = Organization::where('domain', $subdomain)->first();
            }
        } else {
            // {subdomain}.api.reliowork.com or similar 3-part host
            if (count($parts) >= 3) {
                $subdomain = $parts[0];
                $organization = Organization::where('domain', $subdomain)->first();
            }
        }

        // Check for subdomain from URL parameter (for both local and production)
        if (!$subdomain && request()->has('subdomain')) {
            $urlSubdomain = request()->get('subdomain');
            if ($urlSubdomain) {
                $subdomain = $urlSubdomain;
                $organization = Organization::where('domain', $urlSubdomain)->first();
            }
        }

        return view('auth.login-view', [
            'organization' => $organization,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First authenticate user by email/password
        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            
            // Debug: Check if user exists
            $user = \App\Models\User::where('email', $credentials['email'])->first();
            if (!$user) {
                return back()->withErrors(['email' => 'User not found.'])->onlyInput('email');
            }
            
            // Debug: Check password
            if (\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors(['email' => 'Password verification failed in debug.'])->onlyInput('email');
            }
            
            return back()->withErrors(['email' => 'Invalid credentials provided.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();
        
        // Resolve organization from current host (subdomain) and ensure it is verified/active
        $host = $request->getHost();
        $parts = explode('.', $host);

        $subdomain = null;

        if (app()->environment('local')) {
            // Local: {org}.localhost:8000 or plain localhost
            if (count($parts) === 2 && $parts[1] === 'localhost') {
                $subdomain = $parts[0];
            } elseif (count($parts) === 1 && $parts[0] === 'localhost') {
                $subdomain = null;
            } else {
                $subdomain = null;
            }
        } else {
            // Production: derive base host from APP_URL (e.g. api.reliowork.com)
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

        $organization = null;
        if ($subdomain) {
            $organization = Organization::where('domain', $subdomain)
                ->whereNotNull('email_verified_at')
                ->where('active_status', true)
                ->first();
            if (! $organization) {
                // Don't block login - just continue without organization context
                // This allows super admin to login even if org doesn't exist
            }
        }
        
        // Handle super admin login (no organization context)
        if (!$organization) {
            if ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin())) {
                return redirect()->route('cms.admin.dashboard');
            }
            
            // If no organization context and not super admin, logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Please use your organization URL to log in.',
            ])->onlyInput('email');
        }
        
        // Handle organization context login
        if ($organization) {
            if (! $user || $user->organization_id !== $organization->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You are not allowed to access this organization.',
                ])->onlyInput('email');
            }

            // Block inactive volunteers from logging in
            if (! ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin())) && ! $user->is_admin) {
                $volunteer = Volunteer::where('user_id', $user->id)->first();
                if ($volunteer && strcasecmp((string) $volunteer->status, 'Active') !== 0) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->with('error', 'Your account is inactive. Please contact admin.')->onlyInput('email');
                }
            }

            $request->session()->put('organization_id', $organization->id);

            if ($user && (method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin())) {
                return redirect()->route('cms.admin.dashboard');
            }
            if ($user && $user->is_admin) {
                return redirect()->route('cms.dashboard.index');
            }
            if ($user && $user->hasPermission('volunteers')) {
                return redirect()->route('cms.volunteer.index');
            }
            if ($user && $user->hasPermission('families')) {
                return redirect()->route('cms.family.index');
            }
            if ($user && $user->hasPermission('aid_programs')) {
                return redirect()->route('cms.aid-program.index');
            }
            if ($user && $user->hasPermission('financial')) {
                return redirect()->route('cms.financial-management.index');
            }
            if ($user && $user->hasPermission('surveys')) {
                return redirect()->route('cms.survey.index');
            }
            if ($user && $user->hasPermission('reports')) {
                return redirect()->route('cms.report.index');
            }

            if ($user && $user->hasPermission('dashboard')) {
                return redirect()->route('cms.dashboard.index');
            }

            return redirect()->route('cms.profile.index');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
