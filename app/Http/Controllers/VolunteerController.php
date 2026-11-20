<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\Availability;
use App\Models\EducationLevel;
use App\Models\Language;
use App\Models\Organization;
use App\Models\ProjectType;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\VolunteerWelcomeMail;

class VolunteerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:volunteers_list,volunteers_view,volunteers_create,volunteers_edit,volunteers_delete')->only(['index']);
        $this->middleware('permission:volunteers_view')->only(['show']);
        $this->middleware('permission:volunteers_create')->only(['create','store']);
        $this->middleware('permission:volunteers_edit')->only(['edit','update']);
        $this->middleware('permission:volunteers_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $totalVolunteers = Volunteer::where('organization_id', $organizationId)->count();
        $activeThisMonth = Volunteer::where('organization_id', $organizationId)
            ->where('status', 'Active')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        $topVolunteers = Volunteer::where('organization_id', $organizationId)
            ->orderByDesc('joining_date')
            ->take(5)
            ->get(['id', 'full_name', 'joining_date']);

        $recentActivity = Volunteer::where('organization_id', $organizationId)
            ->orderByDesc('updated_at')
            ->take(5)
            ->get(['id', 'full_name', 'updated_at']);

        $volunteers = Volunteer::where('organization_id', $organizationId)
            ->latest()
            ->paginate(10);

        // Hours/points not tracked yet; set placeholders
        $totalHours = 0;
        $avgHours = 0;

        return view('volunteer.volunteer-list', compact(
            'totalVolunteers',
            'activeThisMonth',
            'topVolunteers',
            'recentActivity',
            'volunteers',
            'totalHours',
            'avgHours'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $data['educationLevels'] = EducationLevel::visibleTo($organizationId)->orderBy('name')->get();

        $data['availabilities'] = Availability::visibleTo($organizationId)->orderBy('name')->get();

        $data['projectTypes'] = ProjectType::visibleTo($organizationId)->orderBy('name')->get();

        $data['volunteerRoles'] = VolunteerRole::visibleTo($organizationId)->orderBy('name')->get();

        $data['languages'] = Language::orderBy('name')->get();

        return view('volunteer.volunteer_registration_form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'cnic' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'skills' => ['nullable', 'string'],
            'language_ids' => ['nullable', 'array'],
            'language_ids.*' => ['integer', 'exists:languages,id'],
            'availability_id' => ['nullable', 'exists:availabilities,id'],
            'preferred_project_type_ids' => ['nullable', 'array'],
            'preferred_project_type_ids.*' => ['integer', 'exists:project_types,id'],
            'assigned_project_type_ids' => ['nullable', 'array'],
            'assigned_project_type_ids.*' => ['integer', 'exists:project_types,id'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'volunteer_role_id' => ['nullable', 'exists:volunteer_roles,id'],
            'joining_date' => ['nullable', 'date'],
            'picture' => ['nullable', 'image', 'max:2048'],
            'remarks' => ['nullable', 'string'],
            'is_admin' => ['sometimes', 'boolean'],
            'permissions' => ['sometimes', 'array'],
        ]);

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('volunteers', 'public');
            $picturePath = 'storage/'.$path;
        }

        // Determine organization context for the new volunteer user
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');

        $user = null;
        
        DB::transaction(function () use ($request, $validated, $picturePath, $organizationId, &$user) {
            // Create linked user using provided password
            $permissionsPayload = [];
            $rawPerms = $request->input('permissions', []);
            if (is_array($rawPerms)) {
                foreach ($rawPerms as $key => $value) {
                    $permissionsPayload[$key] = (bool) $value;
                }
            }

            // Ensure volunteers module access by default for volunteer users
            $permissionsPayload['volunteers'] = true;

            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'organization_id' => $organizationId,
                'is_admin' => $request->boolean('is_admin'),
                'permissions' => $permissionsPayload,
            ]);

            // Ensure volunteer role exists and assign
            $roleId = DB::table('roles')->where('slug', 'volunteer')->value('id');
            if (! $roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'Volunteer',
                    'slug' => 'volunteer',
                    'description' => 'Volunteer User',
                    'is_system' => false,
                    'organization_id' => $organizationId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $roleId],
                [
                    'organization_id' => $organizationId, // add this if table has this column
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Generate unique volunteer code
            $volunteerCode = 'VOL-'.strtoupper(Str::random(6));
            while (Volunteer::where('volunteer_code', $volunteerCode)->exists()) {
                $volunteerCode = 'VOL-'.strtoupper(Str::random(6));
            }

            // Create volunteer record
            Volunteer::create([
                'volunteer_code' => $volunteerCode,
                'user_id' => $user->id,
                'organization_id' => $organizationId,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'] ?? null,
                'cnic' => $validated['cnic'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'dob' => $validated['dob'] ?? null,
                'address' => $validated['address'] ?? null,
                'education_level_id' => $validated['education_level_id'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'language_ids' => $validated['language_ids'] ?? null,
                'availability_id' => $validated['availability_id'] ?? null,
                'preferred_project_type_ids' => $validated['preferred_project_type_ids'] ?? null,
                'assigned_project_type_ids' => $validated['assigned_project_type_ids'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'volunteer_role_id' => $validated['volunteer_role_id'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
                'picture_path' => $picturePath,
                'remarks' => $validated['remarks'] ?? null,
                'status' => 'Active',
            ]);
        });

        if ($user) {
            try {
                $org = Organization::find($organizationId);
                if (app()->environment('local')) {
                    // Local: use base domain with subdomain parameter
                    $loginUrl = 'http://localhost:8000/login';
                    if ($org && $org->domain) {
                        $loginUrl .= '?subdomain='.$org->domain;
                    }
                } else {
                    $base = config('app.url');
                    $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
                    $host = parse_url($base, PHP_URL_HOST) ?: 'api.reliowork.com';
                    if ($org && $org->domain) {
                        // Temporary: use base domain with subdomain parameter (until DNS is fixed)
                        $loginUrl = $scheme.'://'.$host.'/login?subdomain='.$org->domain;
                    } else {
                        $loginUrl = $scheme.'://'.$host.'/login';
                    }
                }
                Mail::to($user->email)->send(new VolunteerWelcomeMail($user, (string) $validated['password'], $loginUrl));
            } catch (\Throwable $e) {
                Log::error('Volunteer welcome mail failed: '.$e->getMessage());
            }
        }

        return redirect()->route('cms.volunteer.index')->with('success', 'Record has been saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Volunteer $volunteer)
    {
        return view('volunteer.view-user-profile', compact('volunteer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Volunteer $volunteer)
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $educationLevels = EducationLevel::visibleTo($organizationId)->orderBy('name')->get();
        $availabilities = Availability::visibleTo($organizationId)->orderBy('name')->get();
        $projectTypes = ProjectType::visibleTo($organizationId)->orderBy('name')->get();
        $volunteerRoles = VolunteerRole::visibleTo($organizationId)->orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

        return view('volunteer.volunteer_registration_edit_form', compact(
            'volunteer',
            'educationLevels',
            'availabilities',
            'projectTypes',
            'volunteerRoles',
            'languages'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        $validated = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.($volunteer->user_id ?? 'NULL')],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'cnic' => ['nullable', 'string', 'max:15'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'education_level_id' => ['nullable', 'exists:education_levels,id'],
            'skills' => ['nullable', 'string'],
            'language_ids' => ['nullable', 'array'],
            'language_ids.*' => ['integer', 'exists:languages,id'],
            'availability_id' => ['nullable', 'exists:availabilities,id'],
            'preferred_project_type_ids' => ['nullable', 'array'],
            'preferred_project_type_ids.*' => ['integer', 'exists:project_types,id'],
            'assigned_project_type_ids' => ['nullable', 'array'],
            'assigned_project_type_ids.*' => ['integer', 'exists:project_types,id'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'volunteer_role_id' => ['nullable', 'exists:volunteer_roles,id'],
            'joining_date' => ['nullable', 'date'],
            'picture' => ['nullable', 'image', 'max:2048'],
            'remarks' => ['nullable', 'string'],
            'is_admin' => ['sometimes', 'boolean'],
            'permissions' => ['sometimes', 'array'],
            'status' => ['required', 'in:Active,Inactive'],
        ]);

        $picturePath = $volunteer->picture_path;
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('volunteers', 'public');
            $picturePath = 'storage/'.$path;
        }

        DB::transaction(function () use ($request, $validated, $volunteer, $picturePath) {
            // Update linked user name/email and access if provided
            if ($volunteer->user) {
                $userUpdates = [];
                if (! empty($validated['full_name'])) {
                    $userUpdates['name'] = $validated['full_name'];
                }
                if (! empty($validated['email'])) {
                    $userUpdates['email'] = $validated['email'];
                }

                $permissionsPayload = [];
                $rawPerms = $request->input('permissions', []);
                if (is_array($rawPerms)) {
                    foreach ($rawPerms as $key => $value) {
                        $permissionsPayload[$key] = (bool) $value;
                    }
                }

                $userUpdates['is_admin'] = $request->boolean('is_admin');
                $userUpdates['permissions'] = $permissionsPayload;

                if (! empty($userUpdates)) {
                    $volunteer->user->update($userUpdates);
                }
            }

            $volunteer->update([
                'full_name' => $validated['full_name'] ?? $volunteer->full_name,
                'email' => $validated['email'] ?? $volunteer->email,
                'contact_number' => $validated['contact_number'] ?? $volunteer->contact_number,
                'cnic' => $validated['cnic'] ?? $volunteer->cnic,
                'gender' => $validated['gender'] ?? $volunteer->gender,
                'dob' => $validated['dob'] ?? $volunteer->dob,
                'address' => $validated['address'] ?? $volunteer->address,
                'education_level_id' => $validated['education_level_id'] ?? $volunteer->education_level_id,
                'skills' => $validated['skills'] ?? $volunteer->skills,
                'language_ids' => $validated['language_ids'] ?? $volunteer->language_ids,
                'availability_id' => $validated['availability_id'] ?? $volunteer->availability_id,
                'preferred_project_type_ids' => $validated['preferred_project_type_ids'] ?? $volunteer->preferred_project_type_ids,
                'assigned_project_type_ids' => $validated['assigned_project_type_ids'] ?? $volunteer->assigned_project_type_ids,
                'emergency_contact' => $validated['emergency_contact'] ?? $volunteer->emergency_contact,
                'volunteer_role_id' => $validated['volunteer_role_id'] ?? $volunteer->volunteer_role_id,
                'joining_date' => $validated['joining_date'] ?? $volunteer->joining_date,
                'picture_path' => $picturePath,
                'remarks' => $validated['remarks'] ?? $volunteer->remarks,
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('cms.volunteer.index')->with('success', 'Volunteer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Volunteer $volunteer)
    {
        DB::transaction(function () use ($volunteer) {
            if (method_exists($volunteer, 'aidPrograms')) {
                $volunteer->aidPrograms()->detach();
            }
            $volunteer->delete();
        });

        return redirect()->route('cms.volunteer.index')->with('warning', 'Volunteer deleted successfully.');
    }
}
