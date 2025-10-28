<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['settings'] = Setting::get();
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
        // dd($request->all());
        $validated = $request->validate([
            'organization_name' => ['nullable','string','max:255'],
            'email_address' => ['nullable','string','max:255'],
            'phone_number' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'theme' => ['nullable','in:Light,Dark'],
            'language' => ['nullable','in:English,Urdu'],
        ]);

        $validated['email_notifications'] = $request->boolean('email_notifications');
        $validated['push_notifications'] = $request->boolean('push_notifications');
        $validated['aid_approval_alerts'] = $request->boolean('aid_approval_alerts');
        $validated['two_factor_enabled'] = $request->boolean('two_factor_enabled');
        $validated['session_timeout_enabled'] = $request->boolean('session_timeout_enabled');

        $settings = Setting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            Setting::create($validated);
        }

        return redirect()->route('cms.setting.index')->with('success', 'Settings saved successfully.');
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
        // dd($request->all());
        $validated = $request->validate([
            'organization_name' => ['nullable','string','max:255'],
            'email_address' => ['nullable','string','max:255'],
            'phone_number' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'theme' => ['nullable','in:Light,Dark'],
            'language' => ['nullable','in:English,Urdu'],
        ]);

        $validated['email_notifications'] = $request->boolean('email_notifications');
        $validated['push_notifications'] = $request->boolean('push_notifications');
        $validated['aid_approval_alerts'] = $request->boolean('aid_approval_alerts');
        $validated['two_factor_enabled'] = $request->boolean('two_factor_enabled');
        $validated['session_timeout_enabled'] = $request->boolean('session_timeout_enabled');

        if ($setting && $setting->exists) {
            $setting->update($validated);
        } else {
            $existing = Setting::first();
            if ($existing) { $existing->update($validated); }
            else { Setting::create($validated); }
        }

        return redirect()->route('cms.setting.index')->with('success', 'Settings Updated successfully.');
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
            'current_password' => ['required','string'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $user = $request->user();
        if (!$user || !Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password'])
        ])->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
