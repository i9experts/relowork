<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login-view');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find organization by email and ensure it is verified/active
        $organization = Organization::where('email', $credentials['email'])
            ->where(function ($q) {
                $q->whereNotNull('email_verified_at')
                  ->where('active_status', true);
            })
            ->first();

        if (!$organization) {
            return back()->withErrors([
                'email' => 'Your organization is not verified or does not exist.',
            ])->onlyInput('email');
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
        if (!$user || $user->organization_id !== $organization->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'You are not allowed to access this organization.',
            ])->onlyInput('email');
        }

        // Store organization_id in session for multitenant scoping
        $request->session()->put('organization_id', $organization->id);

        // Hamesha dashboard par redirect karein (organization form par wapas na bheje)
        return redirect()->route('cms.dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
