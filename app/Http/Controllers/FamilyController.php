<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['families'] = Family::with('volunteer')->withCount('members')->get();
        $data['total_families'] = Family::count();
        $data['active_recipients'] = Family::where('zakat_eligibility', true)->count();
        $data['pending_reviews'] = 0; // adjust when a review workflow exists
        return view('families.family-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['volunteer'] = Volunteer::get();
        $data['next_family_code'] = 'FAM-' . str_pad((string)((Family::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);
        return view('families.family-registration-form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'head_of_family_name' => ['required', 'string', 'max:255'],
            'cnic' => ['required', 'string', 'regex:/^\d{5}-\d{7}-\d{1}$/', 'unique:families,cnic'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'age' => ['nullable', 'integer', 'min:0'],
            'marital_status' => ['nullable', 'string', 'in:Married,Widowed,Divorced,Single'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'province' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'gps_lat' => ['nullable', 'numeric'],
            'gps_long' => ['nullable', 'numeric'],
            'family_members_count' => ['nullable', 'integer', 'min:0'],
            'monthly_income' => ['nullable', 'integer', 'min:0'],    
            'income_source' => ['nullable', 'string', 'max:100'],
            'monthly_expense' => ['nullable', 'integer', 'min:0'],
            'zakat_eligibility' => ['required', 'string', 'in:Yes,No'],
            'comment_note' => ['nullable', 'string'],
            'volunteer_id' => ['required', 'exists:volunteers,id'],
            'members' => ['nullable', 'array'],
            'members.*.name' => ['nullable', 'string', 'max:255'],
            'members.*.age' => ['nullable', 'integer', 'min:0'],
            'members.*.relation' => ['nullable', 'string', 'max:100'],
            'members.*.education' => ['nullable', 'string', 'max:100'],
            'members.*.occupation' => ['nullable', 'string', 'max:100'],
            'members.*.cnic' => ['nullable', 'string', 'max:15'],
        ]);

        $familyCode = 'FAM-' . str_pad((string)((Family::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated, $familyCode) {
            $family = Family::create([
                'family_code'           => $familyCode,
                'head_of_family_name'   => $validated['head_of_family_name'],
                'cnic'                  => $validated['cnic'],
                'gender'                => $validated['gender'],
                'age'                   => $validated['age'] ?? null,
                'marital_status'        => $validated['marital_status'] ?? null,
                'contact_number'        => $validated['contact_number'] ?? null,
                'address'               => $validated['address'] ?? null,
                'province'              => $validated['province'] ?? null,
                'city'                  => $validated['city'] ?? null,
                'area'                  => $validated['area'] ?? null,
                'gps_lat'               => $validated['gps_lat'] ?? null,
                'gps_long'              => $validated['gps_long'] ?? null,
                'family_members_count'  => $validated['family_members_count'] ?? 0,
                'monthly_income'        => $validated['monthly_income'] ?? null,
                'income_source'         => $validated['income_source'] ?? null,
                'monthly_expense'       => $validated['monthly_expense'] ?? null,
                'zakat_eligibility'     => ($validated['zakat_eligibility'] === 'Yes'),
                'comment_note'          => $validated['comment_note'] ?? null,
                'volunteer_id'          => $validated['volunteer_id'],
            ]);

            $members = request('members', []);
            if (is_array($members) && count($members)) {
                $toCreate = [];
                foreach ($members as $m) {
                    $name = isset($m['name']) ? trim($m['name']) : '';
                    if ($name === '') { continue; }
                    $toCreate[] = [
                        'name' => $name,
                        'age' => isset($m['age']) && $m['age'] !== '' ? (int)$m['age'] : null,
                        'relation' => $m['relation'] ?? null,
                        'education' => $m['education'] ?? null,
                        'occupation' => $m['occupation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                    ];
                }
                if (!empty($toCreate)) {
                    $family->members()->createMany($toCreate);
                }
            }
        });

        return redirect()->route('cms.family.index')->with('success', 'Family registered successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        $data['family'] = $family->load('members');
        $data['volunteer'] = Volunteer::get();
        return view('families.family-registration-edit-form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'head_of_family_name' => ['required', 'string', 'max:255'],
            'cnic' => ['required', 'string', 'regex:/^\d{5}-\d{7}-\d{1}$/', 'unique:families,cnic,' . $family->id],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'age' => ['nullable', 'integer', 'min:0'],
            'marital_status' => ['nullable', 'string', 'in:Married,Widowed,Divorced,Single'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'province' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'gps_lat' => ['nullable', 'numeric'],
            'gps_long' => ['nullable', 'numeric'],
            'family_members_count' => ['nullable', 'integer', 'min:0'],
            'monthly_income' => ['nullable', 'integer', 'min:0'],    
            'income_source' => ['nullable', 'string', 'max:100'],
            'monthly_expense' => ['nullable', 'integer', 'min:0'],
            'zakat_eligibility' => ['required', 'string', 'in:Yes,No'],
            'comment_note' => ['nullable', 'string'],
            'volunteer_id' => ['required', 'exists:volunteers,id'],
            'members' => ['nullable', 'array'],
            'members.*.name' => ['nullable', 'string', 'max:255'],
            'members.*.age' => ['nullable', 'integer', 'min:0'],
            'members.*.relation' => ['nullable', 'string', 'max:100'],
            'members.*.education' => ['nullable', 'string', 'max:100'],
            'members.*.occupation' => ['nullable', 'string', 'max:100'],
            'members.*.cnic' => ['nullable', 'string', 'max:15'],
        ]);

        DB::transaction(function () use ($family, $validated) {
            $family->update([
                'head_of_family_name'   => $validated['head_of_family_name'],
                'cnic'                  => $validated['cnic'],
                'gender'                => $validated['gender'],
                'age'                   => $validated['age'] ?? null,
                'marital_status'        => $validated['marital_status'] ?? null,
                'contact_number'        => $validated['contact_number'] ?? null,
                'address'               => $validated['address'] ?? null,
                'province'              => $validated['province'] ?? null,
                'city'                  => $validated['city'] ?? null,
                'area'                  => $validated['area'] ?? null,
                'gps_lat'               => $validated['gps_lat'] ?? null,
                'gps_long'              => $validated['gps_long'] ?? null,
                'family_members_count'  => $validated['family_members_count'] ?? 0,
                'monthly_income'        => $validated['monthly_income'] ?? null,
                'income_source'         => $validated['income_source'] ?? null,
                'monthly_expense'       => $validated['monthly_expense'] ?? null,
                'zakat_eligibility'     => ($validated['zakat_eligibility'] === 'Yes'),
                'comment_note'          => $validated['comment_note'] ?? null,
                'volunteer_id'          => $validated['volunteer_id'],
            ]);

            $members = request('members', []);
            $toCreate = [];
            if (is_array($members) && count($members)) {
                foreach ($members as $m) {
                    $name = isset($m['name']) ? trim($m['name']) : '';
                    if ($name === '') { continue; }
                    $toCreate[] = [
                        'name' => $name,
                        'age' => isset($m['age']) && $m['age'] !== '' ? (int)$m['age'] : null,
                        'relation' => $m['relation'] ?? null,
                        'education' => $m['education'] ?? null,
                        'occupation' => $m['occupation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                    ];
                }
            }
            // simple sync: delete and recreate
            $family->members()->delete();
            if (!empty($toCreate)) {
                $family->members()->createMany($toCreate);
            }
            // update cached count
            $family->update(['family_members_count' => count($toCreate)]);
        });

        return redirect()->route('cms.family.index')->with('success', 'Family updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        DB::transaction(function () use ($family) {
            $family->members()->delete();
            $family->delete();
        });
        return redirect()->route('cms.family.index')->with('success', 'Family deleted successfully!');
    }
}
