<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $q = Setting::query();
        if ($organizationId) {
            $q->where('organization_id', $organizationId);
        } else {
            $q->whereNull('organization_id');
        }
        $data['settings'] = $q->get();

        return view('setting.setting-form')->with($data);
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
        try {
            $validated = $request->validate([
                'organization_name' => ['nullable', 'string', 'max:255'],
                'email_address' => ['nullable', 'string', 'max:255'],
                'phone_number' => ['nullable', 'string', 'max:50'],
                'address' => ['nullable', 'string', 'max:500'],
                'session_timeout_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
                'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'favicon' => ['nullable', 'image', 'mimes:png,ico,jpg,jpeg,webp', 'max:1024'],
            ]);

            $organizationId = optional($request->user())->organization_id ?? session('organization_id');

            $validated['organization_id'] = $organizationId;
            $validated['email_notifications'] = $request->boolean('email_notifications');
            $validated['push_notifications'] = $request->boolean('push_notifications');
            $validated['aid_approval_alerts'] = $request->boolean('aid_approval_alerts');
            $validated['two_factor_enabled'] = $request->boolean('two_factor_enabled');
            $validated['session_timeout_enabled'] = $request->boolean('session_timeout_enabled');
            $validated['session_timeout_minutes'] = $request->input('session_timeout_minutes', 10);

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $validated['logo_path'] = $path;
            }
            if ($request->hasFile('favicon')) {
                $fpath = $request->file('favicon')->store('favicons', 'public');
                $validated['favicon_path'] = $fpath;
            }
            unset($validated['logo'], $validated['favicon']);

            $settings = $organizationId
                ? Setting::where('organization_id', $organizationId)->first()
                : Setting::whereNull('organization_id')->first();
            if ($settings) {
                $settings->update($validated);
            } else {
                Setting::create($validated);
            }

            return redirect()->route('cms.setting.index')->with('success', 'Settings saved successfully.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Failed to save settings: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        try {
            $validated = $request->validate([
                'organization_name' => ['nullable', 'string', 'max:255'],
                'email_address' => ['nullable', 'string', 'max:255'],
                'phone_number' => ['nullable', 'string', 'max:50'],
                'address' => ['nullable', 'string', 'max:500'],
                'session_timeout_minutes' => ['nullable', 'integer', 'min:1', 'max:720'],
                'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'favicon' => ['nullable', 'image', 'mimes:png,ico,jpg,jpeg,webp', 'max:1024'],
            ]);

            $validated['email_notifications'] = $request->boolean('email_notifications');
            $validated['push_notifications'] = $request->boolean('push_notifications');
            $validated['aid_approval_alerts'] = $request->boolean('aid_approval_alerts');
            $validated['two_factor_enabled'] = $request->boolean('two_factor_enabled');
            $validated['session_timeout_enabled'] = $request->boolean('session_timeout_enabled');
            $validated['session_timeout_minutes'] = $request->input('session_timeout_minutes', 10);

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $validated['logo_path'] = $path;
            }
            if ($request->hasFile('favicon')) {
                $fpath = $request->file('favicon')->store('favicons', 'public');
                $validated['favicon_path'] = $fpath;
            }
            unset($validated['logo'], $validated['favicon']);

            $organizationId = optional($request->user())->organization_id ?? session('organization_id');

            if ($setting && $setting->exists) {
                $setting->update($validated);
            } else {
                $existing = $organizationId ? Setting::where('organization_id', $organizationId)->first()
                                            : Setting::whereNull('organization_id')->first();
                if ($existing) {
                    $existing->update($validated);
                } else {
                    Setting::create($validated);
                }
            }

            return redirect()->route('cms.setting.index')->with('success', 'Settings Updated successfully.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Failed to update settings: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        if (! $user || ! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('success', 'Password changed successfully.');
    }

    public function profile()
    {
        $user = auth()->user();
        $volunteer = \App\Models\Volunteer::where('user_id', optional($user)->id)->first();
        $organization = optional($user)->organization_id ? \App\Models\Organization::find($user->organization_id) : null;

        return view('setting.profile-form', compact('user', 'volunteer', 'organization'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.optional($request->user())->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if (! $file->isValid()) {
                return back()->withErrors(['avatar' => 'The avatar failed to upload.'])->withInput();
            }
            try {
                $path = $file->store('avatars', 'public');
                $user->avatar_path = $path;
            } catch (\Throwable $e) {
                return back()->withErrors(['avatar' => 'The avatar failed to upload.'])->withInput();
            }
        }

        $user->forceFill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ])->save();

        return redirect()->route('cms.setting.index')->with('success', 'Profile updated successfully.');
    }

    public function sendTestEmail(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        try {
            Mail::raw('This is a test email from Charity CMS settings.', function ($m) use ($user) {
                $m->to($user->email)->subject('Test Email — Charity CMS');
            });

            return back()->with('success', 'Test email sent to '.$user->email.'.');
        } catch (\Throwable $e) {
            Log::error('Test email failed: '.$e->getMessage());

            return back()->with('error', 'Failed to send test email: '.$e->getMessage());
        }
    }
}
