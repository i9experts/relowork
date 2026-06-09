<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        $organization = null;

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
                $organization = Organization::where('domain', $urlSubdomain)->first();
            }
        }

        return view('auth.forgot-password', [
            'organization' => $organization,
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        // Generic response even if user not found
        if (! $user) {
            return back()->with('status', 'If this email is registered, a password reset link has been sent.');
        }

        // Create password reset token (store raw token for simplicity)
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Send reset link email with raw token; on failure provide local fallback
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
            return back()->with('status', 'If this email is registered, a password reset link has been sent.');
        } catch (\Throwable $e) {
            \Log::error('Password reset mail failed: '.$e->getMessage());
            return back()->with('status', 'If this email is registered, a password reset link has been sent.');
        }
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');
        $organization = null;

        $host = $request->getHost();
        $parts = explode('.', $host);

        if (app()->environment('local')) {
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
        if (!$subdomain && $request->has('subdomain')) {
            $urlSubdomain = $request->get('subdomain');
            if ($urlSubdomain) {
                $organization = Organization::where('domain', $urlSubdomain)->first();
            }
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
            'organization' => $organization,
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        if (! $record) {
            return back()->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        // Optional: check token age (e.g., 60 minutes)
        if ($record->created_at && now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['email' => 'This password reset link has expired.']);
        }

        // Verify raw token
        if ($data['token'] !== $record->token) {
            return back()->withErrors(['email' => 'This password reset link is invalid.']);
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'We could not find a user with this email address.']);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset. Please log in.');
    }
}
