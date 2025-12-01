<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationOtpController extends Controller
{
    public function show(Organization $organization)
    {
        if ($organization->email_verified_at) {
            $orgLoginUrl = $this->organizationLoginUrl($organization);

            return redirect()->away($orgLoginUrl)
                ->with('success', 'Your organization is already verified. Please log in.');
        }

        return view('organization.otp-verify', [
            'organization' => $organization,
        ]);
    }

    public function verify(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'otp_code' => ['required', 'string', 'min:4', 'max:10'],
        ]);

        if (! $organization->otp_code || ! $organization->otp_expires_at || now()->greaterThan($organization->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'The verification code has expired. Please request a new one.']);
        }

        if ($data['otp_code'] !== $organization->otp_code) {
            return back()->withErrors(['otp_code' => 'Invalid verification code.']);
        }

        $organization->forceFill([
            'email_verified_at' => now(),
            'active_status' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        $orgLoginUrl = $this->organizationLoginUrl($organization);

        return redirect()->away($orgLoginUrl)
            ->with('success', 'Email verified successfully. Please log in to continue.');
    }

    public function resend(Request $request, Organization $organization)
    {
        if ($organization->email_verified_at) {
            return redirect()->route('login')->with('success', 'Your organization is already verified. Please log in.');
        }

        $organization->otp_code = (string) random_int(100000, 999999);
        $organization->otp_expires_at = now()->addMinutes(5);
        $organization->save();

        try {
            \Mail::to($organization->email)->send(new \App\Mail\OrganizationOtpMail($organization));
        } catch (\Throwable $e) {
            // fail silently for now
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    /**
     * Build the login URL for an organization based on environment.
     */
    protected function organizationLoginUrl(Organization $organization): string
    {
        $domain = $organization->domain;

        if (app()->environment('local')) {
            // Example: http://orgname.localhost:8000/login
            return 'http://'.$domain.'.localhost:8000/login';
        }

        // Production: https://orgname.relowork.com/login
        return 'https://'.$domain.'.relowork.com/login';
    }
}
