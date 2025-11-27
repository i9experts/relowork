<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $u = Auth::user();
            if ($u && (method_exists($u, 'isSystemAdmin') && $u->isSystemAdmin())) {
                return redirect()->route('cms.admin.dashboard');
            }
            return redirect()->route('cms.dashboard.index');
        }

        $organization = null;

        $host = request()->getHost();
        $parts = explode('.', $host);

        if (app()->environment('local')) {
            // Expect {subdomain}.localhost in local dev
            if (count($parts) === 2 && $parts[1] === 'localhost') {
                $subdomain = $parts[0];
                $organization = Organization::where('domain', $subdomain)->first();
            }
        } else {
            // Expect {subdomain}.relowork.com or similar 3-part host
            if (count($parts) >= 3) {
                $subdomain = $parts[0];
                $organization = Organization::where('domain', $subdomain)->first();
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

        // Resolve organization from current host (subdomain) and ensure it is verified/active
        $host = $request->getHost();
        $parts = explode('.', $host);

        $subdomain = null;

        if (app()->environment('local')) {
            if (count($parts) === 2 && $parts[1] === 'localhost') {
                $subdomain = $parts[0];
            } elseif (count($parts) === 1 && $parts[0] === 'localhost') {
                $subdomain = null;
            } else {
                $subdomain = null;
            }
        } else {
            if (count($parts) >= 3) {
                $subdomain = $parts[0];
            } else {
                $subdomain = null;
            }
        }

        $organization = null;
        if ($subdomain) {
            $organization = Organization::where('domain', $subdomain)
                ->whereNotNull('email_verified_at')
                ->where('active_status', true)
                ->first();
            if (!$organization) {
                return back()->withErrors([
                    'email' => 'Your organization is not verified or does not exist.',
                ])->onlyInput('email');
            }
        }

        $remember = (bool) $request->boolean('remember');

        // First authenticate user by email/password
        if (!Auth::attempt([
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            return back()->withErrors([
                'email' => 'Invalid credentials provided.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();
        if ($organization) {
            if (!$user || $user->organization_id !== $organization->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'You are not allowed to access this organization.',
                ])->onlyInput('email');
            }
            $request->session()->put('organization_id', $organization->id);
            return redirect()->route('cms.dashboard.index');
        }

        if (!$user || !(method_exists($user, 'isSystemAdmin') && $user->isSystemAdmin())) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors([
                'email' => 'Please use your organization URL to log in.',
            ])->onlyInput('email');
        }

        return redirect()->route('cms.admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
