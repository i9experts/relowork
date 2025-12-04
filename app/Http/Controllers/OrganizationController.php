<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SystemNotificationMailer;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['organizations'] = Organization::get();

        return view('organization.organization-form')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:organizations,email'],
            'domain' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:organizations,domain'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url', 'max:255'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',        // at least one uppercase letter
                'regex:/[a-z]/',        // at least one lowercase letter
                'regex:/[0-9]/',        // at least one digit
                'regex:/[@$!%*#?&]/',   // at least one special character
            ],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:2048'],
        ], [
            'organization_name.required' => 'The organization name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'domain.required' => 'The domain field is required.',
            'domain.unique' => 'This domain is not available because it is already in use.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'Password must contain uppercase, lowercase, number and special character.',
            'logo.image' => 'The logo must be an image.',
            'logo.mimes' => 'The logo must be a file of type: jpg, png, jpeg, webp.',
            'logo.max' => 'The logo may not be greater than 2048 kilobytes.',
        ]);

        $lastCode = Organization::orderByDesc('id')->value('registration_number');
        if ($lastCode && preg_match('/ORG-(\d+)/', $lastCode, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        $registrationNumber = 'ORG-'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $otpCode = (string) random_int(100000, 999999);

        $organization = Organization::create([
            'registration_number' => $registrationNumber,
            'organization_name' => $validatedData['organization_name'],
            'email' => $validatedData['email'],
            'domain' => $validatedData['domain'],
            'phone' => $validatedData['phone'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'website' => $validatedData['website'] ?? null,
            'password' => Hash::make($validatedData['password']),
            'logo' => $validatedData['logo'] ?? null,
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
            'active_status' => false,
        ]);

        // Create a default settings row for this organization
        Setting::create([
            'organization_id' => $organization->id,
            'organization_name' => $organization->organization_name,
            'email_address' => $organization->email,
            'phone_number' => $organization->phone,
            'address' => $organization->address,
            'logo_path' => $organization->logo,
        ]);

        // Create an admin user for this organization with full module permissions
        $orgAdmin = User::create([
            'name' => $organization->organization_name.' Admin',
            'email' => $organization->email,
            'password' => $validatedData['password'],
            'organization_id' => $organization->id,
            'is_admin' => true,
            'permissions' => [
                'dashboard' => true,
                'families' => true,
                'aid_programs' => true,
                'financial' => true,
                'surveys' => true,
                'reports' => true,
                'volunteers' => true,
                'volunteers_create' => true,
                'volunteers_edit' => true,
                'volunteers_delete' => true,
                'settings' => true,
            ],
            'email_verified_at' => now(),
        ]);

        // Attach org_admin role for RBAC clarity
        try {
            $roleId = \DB::table('roles')->where('slug', 'org_admin')->where('organization_id', $organization->id)->value('id');
            if (! $roleId) {
                $roleId = \DB::table('roles')->insertGetId([
                    'name' => 'Organization Admin',
                    'slug' => 'org_admin',
                    'description' => 'Tenant Organization Administrator',
                    'is_system' => false,
                    'organization_id' => $organization->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            \DB::table('role_user')->updateOrInsert(
                ['user_id' => $orgAdmin->id, 'role_id' => $roleId],
                ['organization_id' => $organization->id, 'created_at' => now(), 'updated_at' => now()]
            );
        } catch (\Throwable $e) {
            // ignore if roles table constraints differ
        }

        try {
            Mail::to($organization->email)->send(new \App\Mail\OrganizationOtpMail($organization));
        } catch (\Throwable $e) {
            // In case of mail failure, we still allow manual OTP retrieval if needed.
            try { SystemNotificationMailer::sendSystemErrorAlert(['message' => 'OTP mail send failed', 'context' => $e->getMessage()]); } catch (\Throwable $e2) {}
        }

        try { SystemNotificationMailer::sendOrganizationRegistered($organization); } catch (\Throwable $e) {}

        return redirect()->route('organization.otp.show', ['organization' => $organization->id])
            ->with('success', 'We\'ve sent a verification code to your email. Please verify to continue.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'organization_name' => ['sometimes','string','max:255'],
            'active_status' => ['sometimes','boolean'],
            'reason' => ['nullable','string','max:500']
        ]);

        $originalActive = (bool) $organization->active_status;
        if (array_key_exists('organization_name', $validated)) {
            $organization->organization_name = $validated['organization_name'];
        }
        if (array_key_exists('active_status', $validated)) {
            $organization->active_status = (bool) $validated['active_status'];
        }
        $organization->save();

        $changedBy = $request->user();
        if ($originalActive !== (bool) $organization->active_status) {
            if ($organization->active_status) {
                try { SystemNotificationMailer::sendOrganizationActivated($organization, ['changedBy' => $changedBy]); } catch (\Throwable $e) {}
            } else {
                try { SystemNotificationMailer::sendOrganizationDeactivated($organization, ['changedBy' => $changedBy, 'reason' => $validated['reason'] ?? null]); } catch (\Throwable $e) {}
                $r = strtolower((string) ($validated['reason'] ?? ''));
                if ($r && (str_contains($r, 'billing') || str_contains($r, 'subscription') || str_contains($r, 'renewal') || str_contains($r, 'plan'))) {
                    try { SystemNotificationMailer::sendBillingAlert($organization, ['reason' => $validated['reason'] ?? null]); } catch (\Throwable $e) {}
                }
            }
        }

        return redirect()->route('cms.organization-form.index')->with('success','Organization updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
