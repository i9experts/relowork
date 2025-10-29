<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalVolunteers = Volunteer::count();
        $activeThisMonth = Volunteer::whereNotNull('joining_date')
            ->whereMonth('joining_date', now()->month)
            ->whereYear('joining_date', now()->year)
            ->count();

        $topVolunteers = Volunteer::orderByDesc('joining_date')
            ->take(5)
            ->get(['id','full_name','joining_date']);

        $recentActivity = Volunteer::orderByDesc('updated_at')
            ->take(5)
            ->get(['id','full_name','updated_at']);

        $volunteers = Volunteer::latest()->paginate(10);

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
        return view('volunteer.volunteer_registration_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'contact_number' => ['nullable','string','max:20'],
            'cnic' => ['nullable','string','max:15'],
            'gender' => ['nullable','in:Male,Female,Other'],
            'dob' => ['nullable','date'],
            'address' => ['nullable','string','max:255'],
            'education_level' => ['nullable','in:Matric,Intermediate,Graduate,Post-Graduate'],
            'skills' => ['nullable','string'],
            'languages' => ['nullable','string','max:255'],
            'availability' => ['nullable','in:Full-time,Part-time,Event-based'],
            'preferred_projects' => ['nullable','array'],
            'preferred_projects.*' => ['string'],
            'emergency_contact' => ['nullable','string','max:20'],
            'role' => ['nullable','in:Field Volunteer,Data Verifier,Supervisor'],
            'joining_date' => ['nullable','date'],
            'picture' => ['nullable','image','max:2048'],
            'remarks' => ['nullable','string'],
        ]);

        $picturePath = null;
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('volunteers', 'public');
            $picturePath = 'storage/'.$path;
        }

        DB::transaction(function () use ($validated, $picturePath) {
            // Create linked user
            $password = Str::random(12);
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => $password,
            ]);

            // Ensure volunteer role exists and assign
            $roleId = DB::table('roles')->where('slug', 'volunteer')->value('id');
            if (!$roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'Volunteer',
                    'slug' => 'volunteer',
                    'description' => 'Volunteer User',
                    'is_system' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $roleId],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Create volunteer record
            Volunteer::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'] ?? null,
                'cnic' => $validated['cnic'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'dob' => $validated['dob'] ?? null,
                'address' => $validated['address'] ?? null,
                'education_level' => $validated['education_level'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'languages' => $validated['languages'] ?? null,
                'availability' => $validated['availability'] ?? null,
                'preferred_projects' => $validated['preferred_projects'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'role' => $validated['role'] ?? null,
                'joining_date' => $validated['joining_date'] ?? null,
                'picture_path' => $picturePath,
                'remarks' => $validated['remarks'] ?? null,
            ]);
        });

        return redirect()->route('cms.volunteer.index')->with('success', 'Record has been saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Volunteer $volunteer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Volunteer $volunteer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Volunteer $volunteer)
    {
        //
    }
}
