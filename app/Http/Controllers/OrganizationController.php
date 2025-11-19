<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

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
            'registration_number' => 'required|unique:organizations,registration_number',
            'organization_name'   => 'required',
            'email'               => 'required|email|unique:organizations,email',
            'phone'               => 'nullable',
            'address'             => 'nullable',
            'website'             => 'nullable|url',
            'password'            => 'nullable|min:6',
            'logo'                => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ],[
            'registration_number.required' => 'The registration number field is required.',
            'registration_number.unique'   => 'The registration number has already been taken.',
            'organization_name.required'   => 'The organization name field is required.',
            'email.required'               => 'The email field is required.',
            'email.email'                  => 'The email must be a valid email address.',
            'email.unique'                 => 'The email has already been taken.',
            'password.min'                 => 'The password must be at least 6 characters.',
            'logo.image'                   => 'The logo must be an image.',
            'logo.mimes'                   => 'The logo must be a file of type: jpg, png, jpeg.',
            'logo.max'                     => 'The logo may not be greater than 2048 kilobytes.',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $organization = Organization::create([
            'registration_number' => $validatedData['registration_number'],
            'organization_name'   => $validatedData['organization_name'],
            'email'               => $validatedData['email'],
            'phone'               => $validatedData['phone'] ?? null,
            'address'             => $validatedData['address'] ?? null,
            'website'             => $validatedData['website'] ?? null,
            'password'            => $validatedData['password'] ?? null,
            'logo'                => $validatedData['logo'] ?? null,
        ]);

        // Create admin user for the organization
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password'] ?? 'password'),
            'organization_id' => $organization->id,
            'email_verified_at' => now(),
        ]);

        // Create default settings for the organization
        Setting::create([
            'organization_name' => $validatedData['organization_name'],
            'email_address' => $validatedData['email'],
            'phone_number' => $validatedData['phone'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'organization_id' => $organization->id,
        ]);

        return redirect()->route('cms.organization-form.index')->with('success', 'Organization created successfully.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
